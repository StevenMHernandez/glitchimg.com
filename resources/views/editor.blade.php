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
                    <a target="_blank" id="shareLink_{{ Auth::user()->provider }}" href="#"><img
                                title="share on {{ Auth::user()->provider }}"
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
                    <a target="_blank" id="gif_shareLink_{{ Auth::user()->provider }}" href="#"><img
                                title="share on {{ Auth::user()->provider }}"
                                id="share" style="border-radius:.5em; height: 72px;"
                                src="/assets/images/{{ Auth::user()->provider }}.png"/>
                    </a>
				</span>
			</span>
    </div>
    <div id="controls">
        <div id="varControls"></div>
        <div id="filter_controls"></div>
        <h2 id="functionName"></h2>
        <span onClick="undo()" id="undo">undo.</span>
        <span onClick="redo()" id="undo">redo.</span>
        <!--<span onmousedown="past()" onmouseup="render()" id="upload">compare to past.</span>-->
        <span onClick="upload()" id="upload">save & share.</span>
        <span id="gif_save_button">save gif.</span>
        <span id="gif_button" style="color: #bb0000">create gif.</span>

        <div id="filters"></div>
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

    function uplC() {
        document.getElementById('fileLoad').click();
    }

    function loadFilter(filter) {
        save();
        var $varControls = $('#varControls');
        switch (filter) {
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
<script data-main="/assets/js/editor.js" src="/assets/bower_components/requirejs/require.js"></script>
</body>
</html>