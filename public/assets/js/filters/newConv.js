define(function (require) {
    'use strict';
    var Vue = require('vue');

    var controls = Vue.extend({
        el: function () {
            return '#filter_controls'
        },
        template: '<span id="filter_controls">no variables.</span>',
        data: function () {
            return {
                store: store,
                threshold: random(0, 255)
            }
        },
        watch: {
            threshold: 'render'
        },
        attached: function () {
            this.render();
        },
        methods: {
            render: function () {
                loading();
                var array = [0, 1, 0, -4, 1, 1, 0, 1, -1];
                var revimgData = this.store.ctx.safe.getImageData(0, 0, width, height);
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
                var parts = Math.abs(array[0]) + Math.abs(array[1]) + Math.abs(array[2]) + Math.abs(array[3]) + Math.abs(array[4]) + Math.abs(array[5]) + Math.abs(array[6]) + Math.abs(array[7]) + Math.abs(array[8]);
                var percent = 1 / parts;
                var y;
                for (i = 0; i < 8; i++) {
                    var mult = array[i];
                    var x = i % 3 - 1;
                    if (i <= 2) {
                        y = -1;
                    }
                    else if (i >= 6) {
                        y = 1;
                    }
                    else {
                        y = 0;
                    }
                    if (mult != 0) {
                        if (mult > 0) {
                            this.store.ctx.main.globalAlpha = percent * mult;
                            this.store.ctx.main.drawImage(this.store.ctx.safe.canvas, x, y);
                        }
                        else {
                            this.store.ctx.main.globalAlpha = percent * mult * -1;
                            this.store.ctx.main.drawImage(this.store.ctx.empty.canvas, x, y);
                        }
                    }
                    render();
                }
                this.store.ctx.main.globalAlpha = 1;
//    ctx.putImageData(revimgData,0,0);
                render();
                loaded();
            }
        }
    });

    return {
        name: "New Convolution",
        controls: controls,
        image: "/assets/images/filters/newConv.jpg"
    }
});