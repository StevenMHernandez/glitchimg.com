var gif, gif_button, gif_save_button, preview, gif_id;
var frames = 0;
var retry = 0;

$(document).ready(function () {
    gif_button = $('#gif_button');
    gif_save_button = $('#gif_save_button');
    gif_save_button.hide();
    gif_button.click(function () {
        start_gif()
    });
    preview = $('#preview')[0];
});

function start_gif() {
    $.ajax({
        type: 'GET',
        url: '/upload/gif'
    }).done(function (data) {
        gif.options.id = data.gif_id;
    });

    gif_save_button.show();
    $('#upload').hide();
    gif = new GIF({
        quality: 10,
        repeat: 0,
        height: preview.height,
        width: preview.width,
        workers: 2,
        workerScript: "/assets/bower_components/gif.js/dist/gif.worker.js"
    });

    gif_button = $('#gif_button');

    gif_button.off("click");
    draw_add_frame_button();
    gif_button.click(function () {
        add_frame()
    });

    gif_save_button.click(function () {
        save_gif()
    });
}

function add_frame() {
    upload(gif.options.id);
    if (frames < 9) {
        gif.addFrame(preview.getContext("2d"), {
            copy: true,
            delay: 100
        });
        frames++;
        draw_add_frame_button();
        console.log(gif);
    } else {
        alert("gifs can only have 9 frames at this moment.\n(So that the gif size is not too large)\n\nsave your gif and make some more!");
    }
}

function draw_add_frame_button() {
    gif_button.html("add frame (" + frames + ")");
    if (frames >= 9) {
        gif_button.css('opacity', '0.3');
    } else {
        gif_button.css('opacity', '1.0');
    }
}

function save_gif() {
    gif.on('finished', function (blob) {
        upload_gif(blob)
    });

    gif.render();

    gif_button.off("click");
    gif_button.html("create gif.");
    gif_button.css('opacity', '1.0');
    gif_button.click(function () {
        start_gif()
    });

    gif_save_button.off("click");
    gif_save_button.hide();
    $('#upload').show();
    frames = 0;
    retry = 0;
}

function upload_gif(blob) {
    if (blob.size < 2000000 || retry > 5) {
        document.getElementById('uploading').innerHTML = '<img src="/assets/images/loading.gif"/> Uploading.';
        $("#uploading").lightbox_me();

        var formData = new FormData();
        formData.append('fname', 'gif_to_upload.gif');
        formData.append('gif_id', gif.options.id);
        formData.append('data', blob);

        $.ajax({
            type: 'POST',
            url: '/upload/gif',
            data: formData,
            processData: false,
            contentType: false
        }).done(function (data) {
            $("#uploading").trigger('close');
            $("#gif_modal").lightbox_me();

            document.getElementById('gif_viewThis').href = data.urls.glitchimg;
            document.getElementById('gif_shareLink_facebook').href = data.urls.facebook;
            document.getElementById('gif_shareLink_twitter').href = data.urls.twitter;
            retry = 0;
        });
    } else {
        retry++;
        gif.abort();
        gif.options.quality += 20;
        gif.render();
    }
}