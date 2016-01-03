requirejs.config({
    baseUrl: '/assets/js',
    paths: {
        "filters": "filters",
        "vue": "../bower_components/vue/dist/vue"
    }
});

var store = {
    'max': 540,
    ctx: {
        'preview': document.getElementById('preview').getContext('2d'),
        'safe': document.getElementById('safe').getContext('2d'),
        'main': document.getElementById('canvas').getContext('2d'),
        'large': document.getElementById('large').getContext('2d'),
        'original': document.getElementById('original').getContext('2d'),
        'empty': document.getElementById('empty').getContext('2d')
    },
    loading: 0,
    currentFilter: null
};

var history = {
    cPushArray: [],
    cStep: -2
};

var width, height, pHeight, pWidth;

//setup
store.ctx.preview.font = "20px Trebuchet ms";
store.ctx.preview.fillText("Click to Upload an Image.", 30, 90);

var loadCheck = document.getElementById('fileLoad');
loadCheck.addEventListener('change', this.load, false);

function load(e) {
    loading();
    var reader = new FileReader();
    reader.onload = function (event) {
        history.cPushArray = [];
        history.cStep = -2;
        var img = new Image();
        img.onload = function () {
            var maxDimension = 1800;
            if (img.height > maxDimension || img.width > maxDimension) {
                if (img.height > img.width) {
                    store.ctx.main.canvas.height = maxDimension;
                    store.ctx.main.canvas.width = (img.width / img.height) * maxDimension;
                }
                else {
                    store.ctx.main.canvas.width = maxDimension;
                    store.ctx.main.canvas.height = (img.height / img.width) * maxDimension;
                }
            }
            else {
                store.ctx.main.canvas.width = img.width;
                store.ctx.main.canvas.height = img.height;
            }
            width = store.ctx.main.canvas.width;
            height = store.ctx.main.canvas.height;
            //get screen sizes
            if (height > store.max || width > store.max) {
                if (height > width) {
                    pHeight = store.max;
                    pWidth = (store.max / height) * width;
                }
                else {
                    pWidth = store.max;
                    pHeight = (store.max / width) * height;
                }
            }
            else {
                pHeight = height;
                pWidth = width;
            }
            store.ctx.main.drawImage(img, 0, 0, width, height);
            original();
            render();
            save();
        };
        img.src = event.target.result;
    };
    reader.readAsDataURL(e.target.files[0]);
    $('#fileLoad').hide(900);
    loaded();
}

// actions
//function past() {
//    var previewTx = preview.getContext('2d');
//    preview.height = pHeight;
//    preview.width = pWidth;
//    previewTx.drawImage(safeTx.canvas, 0, 0, pWidth, pHeight);
//    loaded();
//}
function loading() {
    document.getElementById('loading').innerHTML = 'Loading...';
}
function loaded() {
    document.getElementById('loading').innerHTML = '';
}
function render() {
    var previewTx = preview.getContext('2d');
    preview.height = pHeight;
    preview.width = pWidth;
    previewTx.clearRect(0, 0, pWidth, pHeight);
    previewTx.drawImage(store.ctx.main.canvas, 0, 0, pWidth, pHeight);
    store.loading = false;
}
function undo() {
    if (document.getElementById('varControls').innerHTML != "") {
        save();
        undo();
        console.log('test');
        return;
    }
    console.log(history.cStep);
    if (history.cStep > 0) {
        history.cStep--;
        var canvasPic = new Image();
        canvasPic.src = history.cPushArray[history.cStep];
        canvasPic.onload = function () {
            store.ctx.main.drawImage(canvasPic, 0, 0);
            render();
        }
    }
    removeCurrentFilter();
}
function redo() {
    if (history.cStep < history.cPushArray.length - 1) {
        history.cStep++;
        var canvasPic = new Image();
        canvasPic.src = history.cPushArray[history.cStep];
        canvasPic.onload = function () {
            store.ctx.main.drawImage(canvasPic, 0, 0);
            render();
        }
    }
}
function save() {
    store.ctx.safe.canvas.height = height;
    store.ctx.safe.canvas.width = width;
    store.ctx.safe.drawImage(store.ctx.main.canvas, 0, 0, width, height);
    document.getElementById('varControls').innerHTML = '';
    var saveState = document.getElementById('safe').toDataURL('image/jpeg', .95);
    if (saveState == history.cPushArray[history.cStep]) {
        return;
    }
    store.ctx.main.canvas.removeAttribute("onmousedown");
    history.cStep++;
    if (history.cStep < history.cPushArray.length) {
        history.cPushArray.length = history.cStep >= 0 ? history.cStep : 0;
    }
    history.cPushArray.push(saveState);
    removeCurrentFilter()
}
function removeCurrentFilter() {
    if (store.currentFilter) {
        console.log(store.currentFilter);
        store.currentFilter.component.$destroy();
    }
    store.currentFilter = null;
    $('#filter_controls').html('');
}
function original() {
    store.ctx.original.canvas.height = height;
    store.ctx.original.canvas.width = width;
    store.ctx.original.drawImage(store.ctx.main.canvas, 0, 0, width, height);
}
function upload(gif_id) {
    console.log('upload eheheheh');
    var lHeight, lWidth, ratio, orientation;
    if (!gif_id) {
        document.getElementById('uploading').innerHTML = '<img src="/assets/images/loading.gif"/> Uploading.';
        $("#uploading").lightbox_me();
    }
    save();
    //test resize
    var maxPixels = 2700;
    if (height > width) {
        lHeight = maxPixels;
        lWidth = (maxPixels / height) * width;
    }
    else {
        lHeight = (maxPixels / width) * height;
        lWidth = maxPixels;
    }
    store.ctx.large.canvas.height = lHeight;
    store.ctx.large.canvas.width = lWidth;

    if (lHeight > lWidth) {
        ratio = lWidth / lHeight;
        orientation = 'vertical';
    }
    else {
        ratio = lHeight / lWidth;
        if (ratio == 1) {
            orientation = 'square';
        }
        else {
            orientation = 'horizontal';
        }
    }
    console.log(ratio);

    store.ctx.large.imageSmoothingEnabled = false;
    store.ctx.large.webkitImageSmoothingEnabled = false;
    store.ctx.large.mozImageSmoothingEnabled = false;
    store.ctx.large.drawImage(store.ctx.safe.canvas, 0, 0, lWidth, lHeight);
    $.post('/upload',
        {
            orientation: orientation,
            gif_id: gif_id,
            ratio: ratio,
            preview: document.getElementById('preview').toDataURL('image/jpeg', .95),
            large: document.getElementById('large').toDataURL('image/png')
        },
        function (data) {
            if (!gif_id) {
                $("#uploading").trigger('close');
                $("#modal").lightbox_me();

                document.getElementById('uploading').innerHTML = '';
                document.getElementById('posterLink').href = data.urls.zazzle;
                document.getElementById('printThis').href = data.urls.zazzle;
                document.getElementById('printThisWrappingPaper').href = data.urls.zazzle_wrapping_paper;
                document.getElementById('viewThis').href = data.urls.glitchimg;
                document.getElementById('shareLink_facebook').href = data.urls.facebook;
                document.getElementById('shareLink_twitter').href = data.urls.twitter;
            }
        }
    );
}

// helpers
function random(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function isInt(x) {
    return !isNaN(x) && parseInt(Number(x)) == x && !isNaN(parseInt(x, 10));
}


define(['filters'],
    function (Filters) {}
);