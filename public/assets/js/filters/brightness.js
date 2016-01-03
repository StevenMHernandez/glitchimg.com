define(function (require) {
    'use strict';
    var Vue = require('vue');

    var controls = Vue.extend({
        el: function () {
            return '#filter_controls'
        },
        template: '<span id="filter_controls"><input v-model="value" type="range" min="-128" max="128"></span>',
        data: function () {
            return {
                store: store,
                value: random(-128, 128)
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
                var imgData = this.store.ctx.safe.getImageData(0, 0, width, height);
                for (var i = 0; i < imgData.data.length; i += 4) {
                    imgData.data[i] = imgData.data[i] + this.value;
                    imgData.data[i + 1] = imgData.data[i + 1] + this.value;
                    imgData.data[i + 2] = imgData.data[i + 2] + this.value;
                }
                this.store.ctx.main.putImageData(imgData, 0, 0);
                render();
                loaded();
            }
        }
    });

    return {
        name: "Brightness",
        controls: controls,
        image: "/assets/images/filters/brightness.jpg"
    }
});