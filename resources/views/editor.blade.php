<!DOCTYPE html>
<html>
<head>
    <title>glitch-editor.v2</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="stylesheet" href="/assets/css/style.css">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <script src="/assets/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="/assets/bower_components/context-blender/context_blender.js"></script>
    <script src="/assets/bower_components/Lightbox_me/jquery.lightbox_me.js"></script>
    <script src="/assets/bower_components/blueimp-canvas-to-blob/js/canvas-to-blob.min.js"></script>
    <script src="/assets/bower_components/gif.js/dist/gif.js"></script>
    <script src="/assets/bower_components/gif.js/dist/gif.worker.js"></script>
    <script src="/assets/js/create_gif.js"></script>
    <script>
        function printOver() {
            document.getElementById('printer').src = '/assets/images/print.gif';
        }
        function printOff() {
            document.getElementById('printer').src = '/assets/images/print.png';
        }
        function shareOver() {
            document.getElementById('share').src = '/assets/images/share.gif';
        }
        function shareOff() {
            document.getElementById('share').src = '/assets/images/share.png';
        }
    </script>
</head>
<body id="editor">
@include('layouts._header')
<div class="panzoom">
    <canvas id="preview" onClick="uplC();"></canvas>
</div>
<div id="side-controls">
    <input type="file" id="fileLoad" name="fileLoad">
    <span id="loading"></span>

    <div id="ie">
        <canvas id="canvas"></canvas>
        <canvas id="safe"></canvas>
        <canvas id="empty"></canvas>
        <canvas id="original"></canvas>
        <canvas id="large"></canvas>
            <span id="uploading">
                <span id="uploading"></span>
            </span>
			<span id="modal">
                <a target="_blank" id="viewThis" href="#">
                    <h3>
                        <i class="fa fa-fw fa-zoom"></i>
                        View and share your glitch art.
                    </h3>
                </a>
                <a target="_blank" id="printThis" href="#">
                    <h3>
                        <i class="fa fa-fw fa-picture"></i>
                        Order this as a glitch-poster.
                    </h3>
                </a>
                <a target="_blank" id="printThisWrappingPaper" href="#">
                    <h3>
                        <i class="fa fa-fw fa-gift"></i>
                        Turn this glitch art into wrapping paper.
                        <small>(new)</small>
                    </h3>
                </a>
                <span style="text-align: center">
                    <a target="_blank" id="posterLink" href="#">
                        <img title="buy a print!" id="printer" style="border-radius:.5em; height: 72px;"
                             onmouseover="printOver();"
                             onmouseout="printOff();"
                             src="/assets/images/print.png"/>
                    </a>
                    <a target="_blank" id="shareLink_{{ Auth::user()->provider }}" href="#"><img title="share on {{ Auth::user()->provider }}"
                                                                    id="share" style="border-radius:.5em; height: 72px;"
                                                                    src="/assets/images/{{ Auth::user()->provider }}.png"/>
                    </a>
				</span>
			</span>
			<span id="gif_modal">
                <a target="_blank" id="gif_viewThis" href="#">
                    <h3>
                        <i class="fa fa-fw fa-zoom"></i>
                        View and share your glitch gif.
                    </h3>
                </a>
                <span style="text-align: center">
                    <a target="_blank" id="gif_shareLink_{{ Auth::user()->provider }}" href="#"><img title="share on {{ Auth::user()->provider }}"
                                                                    id="share" style="border-radius:.5em; height: 72px;"
                                                                    src="/assets/images/{{ Auth::user()->provider }}.png"/>
                    </a>
				</span>
			</span>
    </div>
    <div id="controls">
        <div id="varControls"></div>
        <h2 id="functionName"></h2>
        <span onClick="undo()" id="undo">undo.</span>
        <span onClick="redo()" id="undo">redo.</span>
        <!--<span onmousedown="past()" onmouseup="render()" id="upload">compare to past.</span>-->
        <span onClick="upload()" id="upload">save & share.</span>
        <span id="gif_save_button">save gif.</span>
        <span id="gif_button">create gif.</span>

        <div>
            <span onClick="loadFilter('ebit')">
                <img src="/assets/images/filters/8bit.jpg"/>
                8-bit
            </span>
            <span onClick="loadFilter('tone')">
                <img src="/assets/images/filters/tone.jpg"/>
                Tone
            </span>
            <span onClick="loadFilter('noise')">
                <img src="/assets/images/filters/noise.jpg"/>
                Noise
            </span>
            <span onClick="loadFilter('layer')">
                <img src="/assets/images/filters/layer.jpg"/>
                Layer
            </span>
            <span onClick="loadFilter('sin')">
                <img src="/assets/images/filters/sin.jpg"/>
                sin
            </span>
            <span onClick="loadFilter('rcv')">
                <img src="/assets/images/filters/rcv.jpg"/>
                rcv
            </span>
            <span onClick="loadFilter('binary')">
                <img src="/assets/images/filters/binary.jpg"/>
                Binary
            </span>
            <span onClick="loadFilter('intensity')">
                <img src="/assets/images/filters/intensity.jpg"/>
                Intensity
            </span>
            <span onClick="loadFilter('cmyk')">
                <img src="/assets/images/filters/CMYK.jpg"/>
                CMYK
            </span>
            <span onClick="loadFilter('revCmyk')">
                <img src="/assets/images/filters/revCMYK.jpg"/>
                revCMYK
            </span>
            <span onClick="loadFilter('invert')">
                <img src="/assets/images/filters/invert.jpg"/>
                Invert
            </span>
            <span onClick="loadFilter('newConv')">
                <img src="/assets/images/filters/newConv.jpg"/>
                newConv
            </span>
            <span onClick="loadFilter('brightness')">
                <img src="/assets/images/filters/brightness.jpg"/>
                brightness
            </span>
            <span onClick="loadFilter('blurF')">
                <img src="/assets/images/filters/blurF.jpg"/>
                blurF
            </span>
            <span onClick="loadFilter('contrast')">
                <img src="/assets/images/filters/contrast.jpg"/>
                contrast
            </span>
            <span onClick="loadFilter('reduce')">
                <img src="/assets/images/filters/reduce.jpg"/>
                reduce
            </span>
            <span onClick="loadFilter('jpgQ')">
                <img src="/assets/images/filters/jpgQ.jpg"/>
                jpgQ
            </span>
            <span onClick="loadFilter('bright')">
                <img src="/assets/images/filters/bright.jpg"/>
                bright
            </span>
            <span onClick="loadFilter('sort')">
                <img src="/assets/images/filters/sort.jpg"/>
                sort
            </span>
            <span onClick="loadFilter('lineFlip')">
                <img src="/assets/images/filters/lineFlip.jpg"/>
                lineFlip
            </span>
            <span onClick="loadFilter('quick')">
                <img src="/assets/images/filters/quick.jpg"/>
                quick
            </span>
            <span onClick="loadFilter('switchr')">
                <img src="/assets/images/filters/switchr.jpg"/>
                switchr
            </span>
            <span onClick="loadFilter('rando')">
                <img src="/assets/images/filters/rando.jpg"/>
                rando
            </span>
            <span onClick="loadFilter('randoLine')">
                <img src="/assets/images/filters/randoLine.jpg"/>
                randoLine
            </span>
        </div>
    </div>
</div>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var pixn, width, height, cPushArray, cStep, maxDimension, pHeight, pWidth;
    $('.panzoom').height($(document).height() - $('navigation').outerHeight());
    var max = 540;
    var preview = document.getElementById('preview');
    var previewTx = preview.getContext('2d');
    previewTx.font = "20px Trebuchet ms";
    previewTx.fillText("Click to Upload an Image.", 30, 90);
    function uplC() {
        document.getElementById('fileLoad').click();
    }

    safe = document.getElementById('safe');
    safeTx = safe.getContext('2d');
    var loadCheck = document.getElementById('fileLoad');
    loadCheck.addEventListener('change', load, false);
    canvas = document.getElementById('canvas');
    ctx = canvas.getContext('2d');

    function load(e) {
        loading();

        var reader = new FileReader();
        reader.onload = function (event) {
            cPushArray = [];
            cStep = -2;
            var img = new Image();
            img.onload = function () {
                maxDimension = 1800;
                if (img.height > maxDimension || img.width > maxDimension) {
                    if (img.height > img.width) {
                        canvas.height = maxDimension;
                        canvas.width = (img.width / img.height) * maxDimension;
                    }
                    else {
                        canvas.width = maxDimension;
                        canvas.height = (img.height / img.width) * maxDimension;
                    }
                }
                else {
                    canvas.width = img.width;
                    canvas.height = img.height;
                }
                width = canvas.width;
                height = canvas.height;
                //get screen sizes
                if (height > max || width > max) {
                    if (height > width) {
                        pHeight = max;
                        pWidth = (max / height) * width;
                    }
                    else {
                        pWidth = max;
                        pHeight = (max / width) * height;
                    }
                }
                else {
                    pHeight = height;
                    pWidth = width;
                }
                ctx.drawImage(img, 0, 0, width, height);
                original();
                render();
                save();
            };
            img.src = event.target.result;
        };
        reader.readAsDataURL(e.target.files[0]);
        $('#fileLoad').hide(900);
    }
</script>

@include('_filters')

<script>
    function past() {
        var previewTx = preview.getContext('2d');
        preview.height = pHeight;
        preview.width = pWidth;
        previewTx.drawImage(safeTx.canvas, 0, 0, pWidth, pHeight);
        loaded();
    }
    function loading() {
        document.getElementById('loading').innerHTML = 'Loading...';
    }
    function loaded() {
        document.getElementById('loading').innerHTML = '';
    }
    function render() {
        loaded();
        var previewTx = preview.getContext('2d');
        preview.height = pHeight;
        preview.width = pWidth;
        previewTx.clearRect(0, 0, pWidth, pHeight);
        previewTx.drawImage(ctx.canvas, 0, 0, pWidth, pHeight);
        loaded(); //double?
    }
    function undo() {
        if (document.getElementById('varControls').innerHTML != "") {
            save();
            undo();
            return;
        }
        if (cStep > 0) {
            cStep--;
            var canvasPic = new Image();
            canvasPic.src = cPushArray[cStep];
            canvasPic.onload = function () {
                ctx.drawImage(canvasPic, 0, 0);
                render();
            }
        }
    }
    function redo() {
        if (cStep < cPushArray.length - 1) {
            cStep++;
            var canvasPic = new Image();
            canvasPic.src = cPushArray[cStep];
            canvasPic.onload = function () {
                ctx.drawImage(canvasPic, 0, 0);
                render();
            }
        }
    }
    function save() {
        safe.height = height;
        safe.width = width;
        safeTx.drawImage(ctx.canvas, 0, 0, width, height);
        document.getElementById('varControls').innerHTML = '';
        var saveState = document.getElementById('safe').toDataURL('image/jpeg', .95);
        if (saveState == cPushArray[cStep]) {
            return;
        }
        canvas.removeAttribute("onmousedown");
        cStep++;
        if (cStep < cPushArray.length) {
            cPushArray.length = cStep >= 0 ? cStep : 0;
        }
        cPushArray.push(saveState);
    }
    function original() {
        var original = document.getElementById('original');
        var originalTx = original.getContext('2d');
        original.height = height;
        original.width = width;
        originalTx.drawImage(ctx.canvas, 0, 0, width, height);
    }
    function upload(gif_id) {
        var lHeight, lWidth, ratio, orientation;
        if (!gif_id) {
            document.getElementById('uploading').innerHTML = '<img src="/assets/images/loading.gif"/> Uploading.';
            $("#uploading").lightbox_me();
        }
        save();
        //test resize
        var large = document.getElementById('large');
        var largeTx = large.getContext('2d');
        var maxPixels = 2700;
        if (height > width) {
            lHeight = maxPixels;
            lWidth = (maxPixels / height) * width;
        }
        else {
            lHeight = (maxPixels / width) * height;
            lWidth = maxPixels;
        }
        large.height = lHeight;
        large.width = lWidth;

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

        largeTx.imageSmoothingEnabled = false;
        largeTx.webkitImageSmoothingEnabled = false;
        largeTx.mozImageSmoothingEnabled = false;
        largeTx.drawImage(safeTx.canvas, 0, 0, lWidth, lHeight);
        $.post('/upload',
                {
                    orientation: orientation,
                    gif_id: gif_id,
                    ratio: ratio,
                    _token: '{{ csrf_token() }}',
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
    function loadFilter(filter) {
        var pixn;
        save();
        var $varControls = $('#varControls');
        switch (filter) {
            case 'binary':
                pixn = 128;
                $varControls.html('<input onchange="binary(this.value)" onmousemove = "binary(this.value)" value = "128" type = "range" min = "0" max = "255" > ');
                binary(pixn);
                break;
            case 'ebit':
                pixn = 64;
                $varControls.html('<input onchange="ebit(this.value)" onmousemove="ebit(this.value)" onkeydown = "ebit(this.value)" value = "64" id = "range" type = "range" name = "points" min = "16" max="128"> ');
                ebit(pixn);
                break;
            case 'intensity':
                pixn = -128;
                var mousemove = '';
                if (width < 1000 && height < 1000) {
                    mousemove += 'onmousemove="intensity(this.value)" ';
                }
                $varControls.html('<input onchange = "intensity(this.value)" ' + mousemove + ' value = "-128" id = "range" type = "range" name = "points" min = "-255" max = "0" > ');
                intensity(pixn);
                break;
            case 'rcv':
                pixn = Math.floor(Math.random() * 250) + 4;
                $varControls.html('<input onchange="rcv(this.value)" value="' + pixn + '" id="range" type = "range" name = "points" min = "4" max = "255" > ');
                rcv(pixn);
                break;
            case 'noise':
                pixn = -110;
                $varControls.html('<input onchange="noise(this.value)" value="-110" id="range" type = "range" name = "points" min = "-100" max = "-2" > ');
                noise(pixn);
                break;
            case 'sine':
                pixn = 27;
                $varControls.html('<input onchange="sine(this.value)" bonmousemove="sine(this.value)"  value = "27"  id = "range"  type = "range"  name = "points"  min = "3"  max = "27" > ');
                sine(pixn);
                break;
            case 'cmyk':
                pixn = 0;
                $varControls.html('<input onChange="cmyk(this.value)" onmousemove="cmyk(this.value)" type = "range" min = "0" max = "128" value = "0" > ');
                cmyk(pixn);
                break;
            case 'revCmyk':
                pixn = 0;
                $varControls.html('<input onChange="revCmyk(this.value)" onmousemove = "revCmyk(this.value)" type = "range" min = "0" max = "255" value = "0" > ');
                revCmyk(pixn);
                break;
            case 'sin':
                pixn = Math.round(Math.random() * 27);
                $varControls.html('<input onchange="sin(this.value)" value="' + pixn + '" id="range" type = "range" name = "points" min = "3" max = "27" > ');
                sin(pixn);
                break;
            case 'invert':
                $varControls.html('no variables');
                invert();
                break;
            case 'newConv':
                $varControls.html('no variables');
                newConv();
                break;
            case 'brightness':
                pixn = 0;
                $varControls.html('<input onchange="brightness(this.value)" onmousemove = "brightness(this.value)" value = "0" id = "range" type = "range" name = "points" min = "-128" max = "128" > ');
                brightness(pixn);
                break;
            case 'blurF':
                pixn = 0;
                maxL = height >= width ? height : width;
                $varControls.html('<input onchange="blurF(this.value)" onmousemove="blurF(this.value)" value = "0" id = "range" type = "range" name = "points" min = "0" max = "' + maxL + '" > ');
                blurF(pixn);
                break;
            case 'contrast':
                pixn = 1 * 1;
                $varControls.html('<input onchange="contrast(this.value)" onmousemove = "contrast(this.value)" value = "0" id = "range" type = "range" name = "points" min = "-10" max = "10" > ');
                contrast(pixn);
                break;
            case 'reduce':
                pixn = 64 * 1;
                $varControls.html('<input onchange="reduce(this.value)" value="64" id="range" type = "range" name = "points" min = "1" max = "255"> ');
                reduce(pixn);
                break;
            case 'bright':
                pixn = 64;
                $varControls.html('<input onchange="bright(this.value)" onmousemove = "bright(this.value)" value = "64" id = "range" type = "range" name = "points" min = "0" max = "128" > ');
                bright(pixn);
                break;
            case 'switchr':
                $varControls.html('no variables');
                switchr();
                break;
            case 'rando':
                $varControls.html('no variables');
                rando();
                break;
            case 'randoLine':
                $varControls.html('no variables');
                randoLine();
                break;
            case 'jpgQ':
                pixn = 0;
                $varControls.html('<input type="range" value="0" min="2" max="20" onChange = "jpgQ(this.value)" onmousemove = "jpgQ(this.value)" > ');
                jpgQ(pixn);
                break;
            case 'layer':
                $varControls.html('<select onkeyup="layer(this.value)" onChange="layer(this.value);"> <option value = "normal" > Select an option </option> <option value = "screen" > Screen </option> <option value = "multiply" > Multiply </option> <option value = "difference" > Difference </option> <option value = "exclusion" > Exclusion </option> <option value = "add" > Linear Dodge </option> <option value = "lighten" > Lighten </option> <option value = "darken" > Darken </option> <option value = "overlay" > Overlay </option> <option value = "hardlight" > Hard Light </option> <option value = "colordodge" > Color Dodge </option> <option value = "colorburn" > Color Burn </option> </select> ');
                break;
            case 'tone':
//                var toneX, toneA, toneXa, toneW, toneF, toneP, tonePer;
                toneX = 'X';
                toneA = Math.round(width * .01);
                toneXa = width;
                toneW = 'triangle';
                toneF = Math.round(Math.random() * 9);
                toneP = Math.round(Math.random() * 100);
                $('#varRange').onClick = 'toneFreq(this.value)';
                $varControls.html('Keep-BG:<input onChange="toneGen()" id="toneBG" type="checkbox" checked = "true" >  <select onkeyup = "toneWave(this.value)" onChange = "toneWave(this.value)" value = "' + toneW + '" > <option value = "sine" > Sine wave </option> <option value = "square" > Square wave </option> <option value = "triangle" > Triangle wave </option> <option value = "saw" > Sawtooth wave </option> </select><br/> Direction : <select onkeyup = "toneAxis(this.value)" onChange = "toneAxis(this.value)" value = "' + toneX + '" > <option value = "X" > Horizontal </option> <option value = "Y" > Vertical </option> </select><br/>' +
                'Frequency : <input type="number" class = "smallInput" onkeyup = "toneFreq(this.value)" value = "' + toneF + '" > Amplitude : <input type="number" class = "smallInput" onkeyup = "toneAmp(this.value)" value = "' + toneA + '" > px <br/>' +
                'Shift:<input type = "range" value = "' + toneP + '" min = "0" max = "100" onmousemove = "tonePhase(this.value)" onChange = "tonePhase(this.value)" > ');
                tonePer = width / toneF;
                toneGen();
                break;
            case 'quick':
                loadFilter('tone');
                toneAxis('X');
                toneWave('saw');
                toneFreq(9);
                save();
                loadFilter('tone');
                toneAxis('Y');
                toneWave('saw');
                toneFreq(9);
                $varControls.html('no variables');
                break;
            case 'sort':
                pixn = 32;
                $varControls.html('Line size: <input type="number" min=1 value="32" onChange="sort(this.value)">px.');
                sort(pixn);
                break;
            case 'lineFlip':
                pixn = 32;
                $varControls.html('Line size: <input type="number" min=1 value="32" onkeyup="lineFlip(this.value)">px.');
                lineFlip(pixn);
                break;
            case 'lcon':
                $varControls.html('no variables');
                lcon();
                break;
        }
    }
    @if(env('APP_ENV') != 'local')
    preview.addEventListener('contextmenu', function (e) {
        if (e.button === 2) {
            alert('Click "save & share." to save you glitch art');
            e.preventDefault();
            return false;
        }
    }, false);
    @endif
</script>
@include('layouts._analytics')
</body>
</html>