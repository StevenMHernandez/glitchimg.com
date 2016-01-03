define(function (require) {
    'use strict';
    var Vue = require('vue');

    var controls = Vue.extend({
        el: function () {
            return '#filter_controls'
        },
        template: '<span id="filter_controls">Line size: <input type="number" min=1 v-model="line_size">px.</span>',
        data: function () {
            return {
                store: store,
                line_size: random(1, 100)
            }
        },
        watch: {
            line_size: 'render'
        },
        attached: function () {
            this.render();
        },
        methods: {
            render: function () {
                loading();
                console.error("This doesn't work because there is no longer a global 'ebit' function");
                return 0;
                var iterations = Math.ceil(height / this.line_size);
                ebit(iterations);
                ctx.drawImage(safeTx.canvas, 0, 0, width, height);
                var empty = document.getElementById('empty');
                var bawTx = empty.getContext('2d');
                var imgData = bawTx.getImageData(0, 0, 1, iterations);    //width 1 means only first row checked
                var current = 0;    //current row
                for (var iColors = 0; iColors < 255; iColors++) {    //for each color
                    for (var i = 0; i < imgData.data.length; i += 4) {    //for each pixel
                        if (Math.round((imgData.data[i] + imgData.data[i + 1] + imgData.data[i + 2]) / 3) == iColors) {    //check
                            ctx.drawImage(safeTx.canvas, 0, ((i / 4) * this.line_size), width, this.line_size, 0, (current * this.line_size), width, this.line_size);
                            current++;
                        }
                    }
                }
                render();
                loaded();
            }
        }
    });

    return {
        name: "Binary",
        controls: controls,
        image: "/assets/images/filters/binary.jpg"
    }
});