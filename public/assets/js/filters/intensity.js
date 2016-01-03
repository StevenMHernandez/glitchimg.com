define(function (require) {
    'use strict';
    var Vue = require('vue');

    var controls = Vue.extend({
        el: function () {
            return '#filter_controls'
        },
        template: '<span id="filter_controls"><input v-model="threshold" type="range" name="points" min="-255" max="0"/></span>',
        data: function () {
            return {
                store: store,
                threshold: random(-255, 0)
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
                    r = imgData.data[i];
                    g = imgData.data[i + 1];
                    b = imgData.data[i + 2];
                    a = imgData.data[i + 3];
                    if (r > -this.threshold) {
                        r = 255;
                    }
                    if (g > -this.threshold) {
                        g = 255;
                    }
                    if (b > -this.threshold) {
                        b = 255;
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
        name: "Intensity",
        controls: controls,
        image: "/assets/images/filters/intensity.jpg"
    }
});