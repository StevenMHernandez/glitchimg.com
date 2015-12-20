<script>
    //filters
    function binary(x) {    //v.2
        if (!x || x == pixn) return;
        pixn = x * 1;
        loading();

        var bawTx = safe.getContext('2d');
        var imgData = bawTx.getImageData(0, 0, width, height);
        for (var i = 0; i < imgData.data.length; i += 4) {
            r = imgData.data[i] * 1;
            g = imgData.data[i + 1] * 1;
            b = imgData.data[i + 2] * 1;
            a = imgData.data[i + 3] * 1;
            if (r > pixn && g > pixn && b > pixn) {
                r = g = b = 255;
            }
            else {
                r = g = b = 0;
            }
            imgData.data[i] = r;
            imgData.data[i + 1] = g;
            imgData.data[i + 2] = b;
            imgData.data[i + 3] = 255;
        }
        ctx.putImageData(imgData, 0, 0);
        render();
    }
    function ebit(x, p) {    //v.1
        if (x === '' || x == pixn) return;
        pixn = x;
        loading();

        var empty = document.getElementById('empty');
        var emptyTx = empty.getContext('2d');
        if (width >= height) {
            empty.width = pixn;
            empty.height = (pixn / width) * height;
        }
        else {
            empty.height = pixn;
            empty.width = (pixn / height) * width;
        }
        emptyTx.drawImage(safeTx.canvas, 0, 0, empty.width, empty.height);
        ctx.imageSmoothingEnabled = false;
        ctx.webkitImageSmoothingEnabled = false;
        ctx.mozImageSmoothingEnabled = false;
        ctx.clearRect(0, 0, width, height);
        ctx.drawImage(emptyTx.canvas, 0, 0, width, height);

        render();
    }

    function intensity(x) {    //v.1
        if (!x || -x == pixn) return;
        var pixn = -x * 1;
        loading();

        var bawTx = safe.getContext('2d');
        var imgData = bawTx.getImageData(0, 0, width, height);
        for (var i = 0; i < imgData.data.length; i += 4) {
            r = imgData.data[i];
            g = imgData.data[i + 1];
            b = imgData.data[i + 2];
            a = imgData.data[i + 3];
            if (r > pixn) {
                r = 255;
            }
            if (g > pixn) {
                g = 255;
            }
            if (b > pixn) {
                b = 255;
            }
            imgData.data[i] = r;
            imgData.data[i + 1] = g;
            imgData.data[i + 2] = b;
            imgData.data[i + 3] = 255;
        }
        ctx.putImageData(imgData, 0, 0);
        render();
    }
    function rcv(x) {    //v.1
        if (!x || x == pixn || x == 0) return;
        var pixn = x * 1;
        loading();

        var bawTx = safe.getContext('2d');
        var imgData = bawTx.getImageData(0, 0, width, height);
        for (var i = 0; i < imgData.data.length; i += 3 * Math.ceil(pixn / 100)) {
            if (imgData.data[i] == pixn) {
                imgData.data[i] = imgData.data[i + pixn];
            }
            else if (imgData.data[i] > pixn) {
                imgData.data[i] = imgData.data[i + pixn];
            }
            else {
                imgData.data[i] = imgData.data[i - pixn];
            }
        }
        ctx.putImageData(imgData, 0, 0);
        render();
    }
    function noise(x) {    //v.1    //really 8block shift
        if (!x || -x == pixn) return;
        var pixn = x * -1;
        loading();

        var bawTx = safe.getContext('2d');
        var imgData = bawTx.getImageData(0, 0, width, height);
        var size = 256;
        var skip = (width * 4) % size;
        for (var i = 0; i < imgData.data.length; i += size) {
            if (Math.floor(Math.random() * pixn) == 0) {
                r = Math.ceil(Math.random() * pixn);
                for (var a = 0; a < size; a += 1) {
                    for (var b = 0; b < size; b += 1) {
                        imgData.data[i + b + (width * a)] = imgData.data[i + ((r * size) + b) + (width * a)];
                    }
                }
            }
            if (i % (width * 4) * size <= size && i >= width - size) {
                i += width * size;
            }
        }
        ctx.putImageData(imgData, 0, 0);
        render();
    }
    function sine(x) {    //v.1
        if (!x || x == pixn) return;
        var pixn = x * 1;
        loading();

        var bawTx = safe.getContext('2d');
        var imgData = bawTx.getImageData(0, 0, width, height);
        for (var i = 0; i < imgData.data.length; i += 4) {
            if (i % pixn == 0) {
                imgData.data[i] = 0;
                imgData.data[i + 1] = 0;
                imgData.data[i + 2] = 0;
                imgData.data[i + 3] = 127;
            }
            if ((i / 2 + pixn) % pixn == 0) {
                imgData.data[i] = 255;
            }
            if ((i / 3) % pixn == 0) {
                imgData.data[i + 1] = 255;
            }
            if ((i / 2) % pixn == 1) {
                imgData.data[i + 2] = 255;
            }
        }
        ctx.putImageData(imgData, 0, 0);
        render();
    }
    function cmyk(x) {    //v.1
        if (!x || x == pixn) return;
        var pixn = x * 1;
        loading();

        var bawTx = safe.getContext('2d');
        var imgData = bawTx.getImageData(0, 0, width, height);
        for (var i = 0; i < imgData.data.length; i += 4) {
            var r = imgData.data[i];
            var g = imgData.data[i + 1];
            var b = imgData.data[i + 2];
            if (r > b && g > b) {
                imgData.data[i + 2] -= pixn;
            }
            else if (r > g && b > g) {
                imgData.data[i + 1] -= pixn;
            }
            else if (g > r && b > r) {
                imgData.data[i] -= pixn;
            }
        }
        ctx.putImageData(imgData, 0, 0);
        render();
    }
    function revCmyk(x) {    //v.1
        if (!x || x == pixn) return;
        var pixn = x * 1;
        loading();

        var bawTx = safe.getContext('2d');
        var imgData = bawTx.getImageData(0, 0, width, height);
        for (var i = 0; i < imgData.data.length; i += 4) {
            var r = imgData.data[i];
            var g = imgData.data[i + 1];
            var b = imgData.data[i + 2];
            if (r < b && g < b) {
                imgData.data[i + 2] += pixn;
            }
            else if (r < g && b < g) {
                imgData.data[i + 1] += pixn;
            }
            else if (g < r && b < r) {
                imgData.data[i] += pixn;
            }
        }
        ctx.putImageData(imgData, 0, 0);
        render();
    }
    //    function saturation(x) {    //v.1
    //        if (!x) {
    //            save();
    //            var pixn = 0;
    //            $('#varControls').html('<input onChange="saturation(this.value)" onmousemove = "saturation(this.value)" type = "range" min = "-100" max = "100" value = "0" > ');
    //        }
    //        else {
    ////            if (x == pixn) {
    ////                return;
    ////            }
    //            var pixn = x * .01;
    //        }
    //        loading();
    ////        if (pixn < 0) {
    //            console.log('good');
    //            var opac = 255 * Math.abs(pixn);
    //            var bawTx = safe.getContext('2d');
    //            var imgData = bawTx.getImageData(0, 0, width, height);
    //            for (var i = 0; i < imgData.data.length; i += 4) {
    //                var shade = Math.round(imgData.data[i] * .3 + imgData.data[i + 1] * .59 + imgData.data[i + 2] * .11);
    //                imgData[i    ] = 255;
    //                imgData[i + 1] = shade;
    //                imgData[i + 2] = shade;
    ////        imgData[i+3]=255;
    //            }
    //            ctx.putImageData(imgData, 0, 0);
    ////        }
    //        render();
    //    }
    function sin(x) {    //v.1
        if (!x || x == pixn) return;
        var pixn = x * 1;
        loading();

        var safe = document.getElementById('safe');
        var bawTx = safe.getContext('2d');
        var imgData = bawTx.getImageData(0, 0, width, height);
        for (var i = 0; i < imgData.data.length; i += 3 * Math.ceil(pixn)) {
            var s = Math.floor(Math.sin(i * pixn + i) * 128 + 128);
            var s2 = Math.floor(Math.sin(i * pixn) * 128 + 128);
            imgData.data[i] = s;
            imgData.data[i + 1] = s2;
        }
        ctx.putImageData(imgData, 0, 0);
        render();
    }
    function invert() {
        var bawTx = safe.getContext('2d');
        var imgData = bawTx.getImageData(0, 0, width, height);
        for (var i = 0; i < imgData.data.length; i += 4) {
            imgData.data[i] = 255 - imgData.data[i];
            imgData.data[i + 1] = 255 - imgData.data[i + 1];
            imgData.data[i + 2] = 255 - imgData.data[i + 2];
        }
        ctx.putImageData(imgData, 0, 0);
        render();
    }
    function newConv() {
        array = [0, 1, 0, -4, 1, 1, 0, 1, -1];
        var bawTx = safe.getContext('2d');
        var imgData = bawTx.getImageData(0, 0, width, height);
        var revimgData = bawTx.getImageData(0, 0, width, height);
        for (var i = 0; i < revimgData.data.length; i += 4) {
            revimgData.data[i] = revimgData.data[i] - (255 - revimgData.data[i]);
            revimgData.data[i + 1] = revimgData.data[i + 1] - (255 - revimgData.data[i + 1]);
            revimgData.data[i + 2] = revimgData.data[i + 2] - (255 - revimgData.data[i + 2]);
        }
        var empty = document.getElementById('empty');
        var emptyTx = empty.getContext('2d');
        empty.height = height;
        empty.width = width;
        emptyTx.putImageData(revimgData, 0, 0);
        parts = Math.abs(array[0]) + Math.abs(array[1]) + Math.abs(array[2]) + Math.abs(array[3]) + Math.abs(array[4]) + Math.abs(array[5]) + Math.abs(array[6]) + Math.abs(array[7]) + Math.abs(array[8]);
        percent = 1 / parts;
        for (var iI = 0; iI < 8; iI++) {
            var mult = array[iI];
            var x = iI % 3 - 1;
            if (iI <= 2) {
                var y = -1;
            }
            else if (iI >= 6) {
                var y = 1;
            }
            else {
                var y = 0;
            }
            if (mult != 0) {
                if (mult > 0) {
                    ctx.globalAlpha = percent * mult;
                    ctx.drawImage(safeTx.canvas, x, y);
                }
                else {
                    ctx.globalAlpha = percent * mult * -1;
                    ctx.drawImage(emptyTx.canvas, x, y);
                }
            }
            render();
        }
        ctx.globalAlpha = 1;
//    ctx.putImageData(revimgData,0,0);
        render();
    }
    function brightness(x) {    //v.1
        if (x === '' || !x || x * 1 == pixn) return;
        var pixn = x * 1;
        loading();

        var bawTx = safe.getContext('2d');
        var imgData = bawTx.getImageData(0, 0, width, height);
        for (var i = 0; i < imgData.data.length; i += 4) {
            imgData.data[i] = imgData.data[i] + pixn;
            imgData.data[i + 1] = imgData.data[i + 1] + pixn;
            imgData.data[i + 2] = imgData.data[i + 2] + pixn;
        }
        ctx.putImageData(imgData, 0, 0);
        render();
    }
    function blurF(x) {    //v.1
        if (!x || x == pixn) return;
        pixn = x * 1;
        loading();

        ctx.globalAlpha = .125;
        ctx.drawImage(safeTx.canvas, -pixn, -pixn);
        ctx.drawImage(safeTx.canvas, 0, -pixn);
        ctx.drawImage(safeTx.canvas, pixn, -pixn);
        ctx.drawImage(safeTx.canvas, -pixn, 0);
        ctx.drawImage(safeTx.canvas, pixn, 0);
        ctx.drawImage(safeTx.canvas, -pixn, pixn);
        ctx.drawImage(safeTx.canvas, 0, pixn);
        ctx.drawImage(safeTx.canvas, pixn, pixn);
        ctx.globalAlpha = 1;
        render();
    }
    function contrast(x) {    //v.1
        if (!x || ((x * .1) + 1) == pixn) return;
        var pixn = ((x * .1) + 1);
        loading();

        var bawTx = safe.getContext('2d');
        var imgData = bawTx.getImageData(0, 0, width, height);
        for (var i = 0; i < imgData.data.length; i += 4) {
            imgData.data[i] = pixn * (imgData.data[i] - 128) + 128;
            imgData.data[i + 1] = pixn * (imgData.data[i + 1] - 128) + 128;
            imgData.data[i + 2] = pixn * (imgData.data[i + 2] - 128) + 128;
        }
        ctx.putImageData(imgData, 0, 0);
        render();
    }
    function reduce(x) {    //v.1
        if (!x || x * 1 == pixn) return;
        var pixn = x * 1;
        loading();

        var bawTx = safe.getContext('2d');
        var imgData = bawTx.getImageData(0, 0, width, height);
        for (var i = 0; i < imgData.data.length; i += 4) {
            imgData.data[i] = Math.round(imgData.data[i] / pixn) * pixn;
            imgData.data[i + 1] = Math.round(imgData.data[i + 1] / pixn) * pixn;
            imgData.data[i + 2] = Math.round(imgData.data[i + 2] / pixn) * pixn;
        }
        ctx.putImageData(imgData, 0, 0);
        render();
    }
    function bright(x) {    //v.1
        if (!x || x == pixn) return;
        var pixn = x * 1;
        loading();

        var bawTx = safe.getContext('2d');
        var imgData = bawTx.getImageData(0, 0, width, height);
        for (var i = 0; i < imgData.data.length; i += 4) {
            imgData.data[i] = Math.abs(imgData.data[i] - pixn);
            imgData.data[i + 1] = Math.abs(imgData.data[i + 1] - pixn);
            imgData.data[i + 2] = Math.abs(imgData.data[i + 2] - pixn);
        }
        ctx.putImageData(imgData, 0, 0);
        render();
    }
    function switchr(x) {    //v.1
        var currenti = 0;
        loading();
        var bawTx = safe.getContext('2d');
        var imgData = bawTx.getImageData(0, 0, width, height);
        for (var i = 0; i < imgData.data.length; i += 3) {
            imgData.data[i] = imgData.data[currenti];
            imgData.data[i + 1] = imgData.data[currenti + 1];
            imgData.data[i + 2] = imgData.data[currenti + 2];
            if (imgData.data[i] > 128) {
                currenti += 3;
            }
            else if (imgData.data[i] <= 128) {
                currenti += 4;
            }
            else {
                current = 0;
            }
        }
        ctx.putImageData(imgData, 0, 0);
        render();
    }
    function rando() {    //v.1
        loading();
        var bawTx = safe.getContext('2d');
        var imgData = bawTx.getImageData(0, 0, width, height);
        area = width * height / 4;
        for (var i = 0; i < imgData.data.length; i += 4) {
            var x = Math.ceil(Math.random() * area) * 4;
            imgData.data[i] = imgData.data[x];
            imgData.data[i + 1] = imgData.data[x + 1];
            imgData.data[i + 2] = imgData.data[x + 2];
        }
        ctx.putImageData(imgData, 0, 0);
        render();
    }
    function randoLine() {    //v.1
        save();
        loading();
        var bawTx = safe.getContext('2d');
        var imgData = bawTx.getImageData(0, 0, width, height);
        area = width;
        for (var i = 0; i < imgData.data.length; i += 4) {
            var x = Math.ceil(Math.random() * area);
            imgData.data[i] = imgData.data[x];
            imgData.data[i + 1] = imgData.data[x + 1];
            imgData.data[i + 2] = imgData.data[x + 2];
        }
        ctx.putImageData(imgData, 0, 0);
        render();
    }
    function jpgQ(x) {    //v.1    //I've found 0-2 to be the same hence the min below
        if (!x || x * .01 == pixn) return;
        var pixn = x * .01;

        safeTx = safe.getContext('2d');
        ctx.drawImage(safeTx.canvas, 0, 0);

        linkP = canvas.toDataURL('image/jpeg', pixn);
        var base64 = new Image();
        base64.src = linkP;
        base64.onload = function () {
            ctx.drawImage(base64, 0, 0, width, height);
            render();
        };
    }
    //multiuse filters
    //    function conv(a, b, c, d, e, f, g, h, i) {    //v.1
    //        var w = width;
    //        var h = height;
    //        var safe = document.getElementById('safe');
    //        var bawTx = safe.getContext('2d');            //baw is black and white original filter name
    //        var imgData = bawTx.getImageData(0, 0, width, height);
    //        for (var i = 0; i < imgData.data.length; i += 4) {
    //            if (i < 4 * w - 4) {
    //
    //                var pixels = [imgData.data[i - ((w - 2) * 4)], imgData.data[i - ((w - 1) * 4)], imgData.data[i - ((w) * 4)],
    //                    imgData.data[i - 4], imgData.data[i], imgData.data[i + 4],
    //                    imgData.data[i + ((w) * 4)], imgData.data[i + ((w + 1) * 4)], imgData.data[i + ((w + 2) * 4)]];
    //            }
    //            else if (i > ((4 * w * h) - (w * 4)) - 4) {
    //                imgData.data[i + 1] = 255;
    //            }
    //            if (i % (w * 4) == 0) {
    //                imgData.data[i] = 255;
    //                imgData.data[i + 2] = 255;
    //            }
    //            else if (((i + 4) / 4) % w == 0) {
    //                imgData.data[i + 1] = 255;
    //                imgData.data[i + 2] = 255;
    //            }
    //        }
    //        ctx.putImageData(imgData, 0, 0);
    //        render();
    //    }
    function layer(x) {    //v.1
        loading();
        if (!x || x == pixn) return;
        else if (x == 'normal') {
            var safeTx = safe.getContext('2d');
            ctx.drawImage(safeTx.canvas, 0, 0);
        }
        else {
            loading();
            var safeTx = safe.getContext('2d');
            ctx.drawImage(safeTx.canvas, 0, 0);
            var pixn = x;
//variable end;

            var original = document.getElementById('original');
            var originalTx = original.getContext('2d');
            originalTx.blendOnto(ctx, pixn);
        }
        render();
    }
    function quick() {
        for (var i = 0; i <= 8; i++) {
            tone();
            toneAxis('X');
            toneWave('saw');
            toneFreq(9);
            save();
            tone();
            toneAxis('Y');
            toneWave('saw');
            toneFreq(9);
            save();

        }
    }
    function toneAxis(x) {
        if (x == toneX) {
            return;
        }
        toneX = x;
        if (x == 'X') {
            toneXa = width;
        }
        else {
            toneXa = height;
        }
        toneGen();
    }
    function toneWave(x) {
        if (x == toneW) {
            return;
        }
        toneW = x;
        toneGen();
    }
    function toneFreq(x) {
        if (x == toneF || x === '') {
            return;
        }
        toneF = x * 1;
        tonePer = toneXa / x;
        toneGen();
    }
    function toneAmp(x) {
        if (x == toneA || x === '') {
            return;
        }
        toneA = x * 1;
        toneGen();
    }
    function tonePhase(x) {
        if (x == toneP || x === '') {
            return;
        }
        toneP = (x / 100) * toneXa;
//    toneP=x*(toneXa/100);
        toneGen();
    }
    function toneGen() {
//    canvas.width='0';
//    canvas.width=width;
        var safeTx = safe.getContext('2d');
        if (!$('#toneBG').attr("checked")) {
            ctx.fillStyle = "#FFFFFF";
            ctx.fillRect(0, 0, width, height);
        }
        else {
            ctx.drawImage(safeTx.canvas, 0, 0);
        }
//sine
        if (toneW == 'sine') {
            if (toneX == 'X') {
                for (var i = 0; i < width; i++) {
                    var t = i + toneP;
                    var shift = toneA * Math.sin(2 * Math.PI * toneF * (t / width));
                    ctx.drawImage(safeTx.canvas, i, 0, 1, height, i, shift, 1, height);
                }
            }
            else {
                for (var i = 0; i < height; i++) {
                    var t = i + toneP;
                    var shift = toneA * Math.sin(2 * Math.PI * toneF * (t / width));
                    ctx.drawImage(safeTx.canvas, 0, i, width, 1, shift, i, width, 1);
                }
            }
        }
        if (toneW == 'saw') {
            if (toneX == 'X') {
                for (var i = 0; i < width; i++) {
                    var t = i - toneP;
                    var shift = toneA * 2 * ((t / tonePer) - Math.floor(.5 + (t / tonePer)));
                    ctx.drawImage(safeTx.canvas, i, 0, 1, height, i, shift, 1, height);
                }
            }
            else {
                for (var i = 0; i < height; i++) {
                    var t = i - toneP;
                    var shift = toneA * 2 * ((t / tonePer) - Math.floor(.5 + (t / tonePer)));
                    ctx.drawImage(safeTx.canvas, 0, i, width, 1, shift, i, width, 1);

                }
            }
        }
        if (toneW == 'triangle') {
            if (toneX == 'X') {
                for (var i = 0; i < width; i++) {
                    var t = i - toneP;
                    var a = tonePer;
                    var shift = toneA * ((2 / a) * (t - a * Math.floor((t / a) + .5))) * Math.pow(-1, Math.floor((t / a) + .5));
                    ctx.drawImage(safeTx.canvas, i, 0, 1, height, i, shift, 1, height);
                }
            }
            else {
                for (var i = 0; i < height; i++) {
                    var t = i - toneP;
                    var a = tonePer;
                    var shift = toneA * ((2 / a) * (t - a * Math.floor((t / a) + .5))) * Math.pow(-1, Math.floor((t / a) + .5));
                    ctx.drawImage(safeTx.canvas, 0, i, width, 1, shift, i, width, 1);

                }
            }
        }
        if (toneW == 'square') {
            if (toneX == 'X') {
                for (var i = 0; i < width; i++) {
                    var t = i - toneP;
                    var shift = Math.sin(t / tonePer * 3);
                    if (shift > 0) {
                        shift = toneA;
                    }
                    else if (shift < 0) {
                        shift = -toneA;
                    }
                    else {
                        shift = 0;
                    }
                    ctx.drawImage(safeTx.canvas, i, 0, 1, height, i, shift, 1, height);
                }
            }
            else {
                for (var i = 0; i < height; i++) {
                    var t = i - toneP;
                    var shift = Math.sin(t / tonePer * 3);
                    if (shift > 0) {
                        shift = toneA;
                    }
                    else if (shift < 0) {
                        shift = -toneA;
                    }
                    else {
                        shift = 0;
                    }
                    ctx.drawImage(safeTx.canvas, 0, i, width, 1, shift, i, width, 1);
                }
            }
        }
        if (toneW == 'random') {
            var random = Math.round((Math.random() * (2 * toneA)) - toneA);
            var shiftSign = 0;
            if (toneX == 'X') {
                for (var i = 0; i < width; i++) {
                    var shift = Math.sin(i / tonePer * 3);

                    if (shift > 0 && shiftSign == 0) {
                        random = Math.round((Math.random() * (2 * toneA)) - toneA);
                        shiftSign = 1;
                    } else if (shift < 0 && shiftSign == 1) {
                        random = Math.round((Math.random() * (2 * toneA)) - toneA);
                        shiftSign = 0;
                    }

                    ctx.drawImage(safeTx.canvas, i, 0, 1, height, i, random, 1, height);
                }
            }
            else {
                for (var i = 0; i < height; i++) {
                    var shift = Math.sin(i / tonePer * 3);

                    if (shift > 0 && shiftSign == 0) {
                        random = Math.round((Math.random() * (2 * toneA)) - toneA);
                        shiftSign = 1;
                    } else if (shift < 0 && shiftSign == 1) {
                        random = Math.round((Math.random() * (2 * toneA)) - toneA);
                        shiftSign = 0;
                    }

//                    ctx.drawImage(safeTx.canvas, i, 0, 1, height, i, random, 1, height);
                    ctx.drawImage(safeTx.canvas, 0, i, width, 1, random, i, width, 1);

                }
            }
        }
        render();
    }
    function sort(x) {    //v.1
        if (!x) return;
        var pixn = x * 1;

        var iterations = Math.ceil(height / pixn);
        ebit(iterations);
        ctx.drawImage(safeTx.canvas, 0, 0, width, height);
        var empty = document.getElementById('empty');
        var bawTx = empty.getContext('2d');
        var imgData = bawTx.getImageData(0, 0, 1, iterations);    //width 1 means only first row checked
        var current = 0;    //current row
        for (var iColors = 0; iColors < 255; iColors++) {    //for each color
            for (var i = 0; i < imgData.data.length; i += 4) {    //for each pixel
                if (Math.round((imgData.data[i] + imgData.data[i + 1] + imgData.data[i + 2]) / 3) == iColors) {    //check
                    ctx.drawImage(safeTx.canvas, 0, ((i / 4) * pixn), width, pixn, 0, (current * pixn), width, pixn);
                    current++;
                }
            }
        }
        render();
    }
    function lineFlip(x) {    //v.1
        if (!x || x <= 0 || x == pixn) return;
        var pixn = x * 1;

        var empty = document.getElementById('empty');
        var bawTx = empty.getContext('2d');
        var current = 0;    //current row
        for (var i = 0; i < height; i += pixn) {    //for each pixel
            ctx.translate(width, 0);
            ctx.scale(-1, 1);
            ctx.drawImage(safeTx.canvas, 0, i, width, pixn, 0, i, width, pixn);
        }
        render();
    }
    function lcon(x) {    //v.1
        save();
        loading();
        var bawTx = safe.getContext('2d');
        var imgData = bawTx.getImageData(0, 0, width, height);
        for (var i = 0; i < imgData.data.length; i += 4) {
            if (!imgData.data[i - 4] || i % (width * 4) == 1) {
                imgData.data[i] = (imgData.data[i] * (imgData.data[i] * 3) * imgData.data[i + 4]) / 5;
                imgData.data[i + 1] = (imgData.data[i] * (imgData.data[i] * 3) * imgData.data[i + 5]) / 5;
                imgData.data[i + 2] = (imgData.data[i] * (imgData.data[i] * 3) * imgData.data[i + 6]) / 5;
            }
            else if (!imgData.data[i + 4] || i % (width * 4) == 0) {
                imgData.data[i] = (imgData.data[i - 4] * (imgData.data[i] * 3) * imgData.data[i]) / 5;
                imgData.data[i + 1] = (imgData.data[i - 3] * (imgData.data[i] * 3) * imgData.data[i]) / 5;
                imgData.data[i + 2] = (imgData.data[i - 2] * (imgData.data[i] * 3) * imgData.data[i]) / 5;
            }
            else {
                imgData.data[i] = (imgData.data[i - 4] * (imgData.data[i] * 3) * imgData.data[i + 4]) / 5;
                imgData.data[i + 1] = (imgData.data[i - 3] * (imgData.data[i] * 3) * imgData.data[i + 5]) / 5;
                imgData.data[i + 2] = (imgData.data[i - 2] * (imgData.data[i] * 3) * imgData.data[i + 6]) / 5;
            }
        }
        ctx.putImageData(imgData, 0, 0);
        render();
    }
</script>