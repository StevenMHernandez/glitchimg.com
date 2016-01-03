define(function (require) {
    'use strict';
    var Vue = require('vue');

    var controls = Vue.extend({
        el: function () {
            return '#filter_controls'
        },
        template: '<span id="filter_controls"><input v-model="entropy" id="range" type="range" name="points" min="-100" max="-2" ></span>',
        data: function () {
            return {
                store: store,
                entropy: random(-100, -2)
            }
        },
        methods: {
            render: function () {
                loading();

                var imgData = this.store.ctx.safe.getImageData(0, 0, width, height);
                var size = 256;
                var skip = (width * 4) % size;
                var r;
                for (var i = 0; i < imgData.data.length; i += size) {
                    if (Math.floor(Math.random() * -this.entropy) == 0) {
                        r = Math.ceil(Math.random() * -this.entropy);
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
                this.store.ctx.main.putImageData(imgData, 0, 0);
                render();
                loaded();
            }
        },
        watch: {
            entropy: 'render'
        },
        attached: function () {
            this.render();
        },
        destroy: function () {
            console.log('noise destroyed');
        }
    });

    return {
        name: "block-noise",
        controls: controls,
        image: "/assets/images/filters/noise.jpg"
    }
});