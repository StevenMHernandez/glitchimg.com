define(function (require) {
    'use strict';
    var Vue = require('vue');

    var controls = Vue.extend({
        el: function () {
            return '#filter_controls'
        },
        template: '<span id="filter_controls"><input v-model="threshold" type="range" min="0" max="128"></span>',
        data: function () {
            return {
                store: store,
                threshold: random(0, 128)
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
                var r, g, b;
                var imgData = this.store.ctx.safe.getImageData(0, 0, width, height);
                for (var i = 0; i < imgData.data.length; i += 4) {
                    r = imgData.data[i];
                    g = imgData.data[i + 1];
                    b = imgData.data[i + 2];
                    if (r < b && g < b) {
                        imgData.data[i + 2] += this.threshold;
                    }
                    else if (r < g && b < g) {
                        imgData.data[i + 1] += this.threshold;
                    }
                    else if (g < r && b < r) {
                        imgData.data[i] += this.threshold;
                    }
                }
                this.store.ctx.main.putImageData(imgData, 0, 0);
                loaded();
                render();
            }
        }
    });

    return {
        name: "revCMYK",
        controls: controls,
        image: "/assets/images/filters/revCmyk.jpg"
    }
});