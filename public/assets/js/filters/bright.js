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
                var imgData = this.store.ctx.safe.getImageData(0, 0, width, height);
                for (var i = 0; i < imgData.data.length; i += 4) {
                    imgData.data[i] = Math.abs(imgData.data[i] - this.threshold);
                    imgData.data[i + 1] = Math.abs(imgData.data[i + 1] - this.threshold);
                    imgData.data[i + 2] = Math.abs(imgData.data[i + 2] - this.threshold);
                }
                this.store.ctx.main.putImageData(imgData, 0, 0);
                render();
                loaded();
            }
        }
    });

    return {
        name: "Bright",
        controls: controls,
        image: "/assets/images/filters/bright.jpg"
    }
});