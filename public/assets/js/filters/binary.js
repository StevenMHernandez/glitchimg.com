define(function (require) {
    'use strict';
    var Vue = require('vue');

    var controls = Vue.extend({
        el: function () {
            return '#filter_controls'
        },
        template: '<span id="filter_controls"><input v-model="threshold" type=range" min="0" max="255" ></span>',
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
                var r, g, b, a;
                var imgData = this.store.ctx.safe.getImageData(0, 0, width, height);
                for (var i = 0; i < imgData.data.length; i += 4) {
                    r = imgData.data[i] * 1;
                    g = imgData.data[i + 1] * 1;
                    b = imgData.data[i + 2] * 1;
                    a = imgData.data[i + 3] * 1;
                    if (r > this.threshold && g > this.threshold && b > this.threshold) {
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
                this.store.ctx.main.putImageData(imgData, 0, 0);
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