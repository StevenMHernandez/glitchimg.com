define(function (require) {
    'use strict';
    var Vue = require('vue');

    var controls = Vue.extend({
        el: function () {
            return '#filter_controls'
        },
        template: '<span id="filter_controls"><input v-model="value" type="range" min="0" max="255"></span>',
        data: function () {
            return {
                store: store,
                value: random(0, 255)
            }
        },
        watch: {
            value: 'render'
        },
        attached: function () {
            this.render();
        },
        methods: {
            render: function () {
                loading();
                var value = this.value * 0.1 + 1; // the plus one might actually make this filter less interesting
                var imgData = this.store.ctx.safe.getImageData(0, 0, width, height);
                for (var i = 0; i < imgData.data.length; i += 4) {
                    imgData.data[i] = value * (imgData.data[i] - 128) + 128;
                    imgData.data[i + 1] = value * (imgData.data[i + 1] - 128) + 128;
                    imgData.data[i + 2] = value * (imgData.data[i + 2] - 128) + 128;
                }
                this.store.ctx.main.putImageData(imgData, 0, 0);
                render();
                loaded();
            }
        }
    });

    return {
        name: "Contrast",
        controls: controls,
        image: "/assets/images/filters/contrast.jpg"
    }
});