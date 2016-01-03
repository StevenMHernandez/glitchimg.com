define(function (require) {
    'use strict';
    var Vue = require('vue');

    var controls = Vue.extend({
        el: function () {
            return '#filter_controls'
        },
        template: '<span id="filter_controls"><input v-model="value" type="range" name="points" min="3" max="27"></span>',
        data: function () {
            return {
                store: store,
                value: random(3, 27)
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
                    if (i % this.value == 0) {
                        imgData.data[i] = 0;
                        imgData.data[i + 1] = 0;
                        imgData.data[i + 2] = 0;
                        imgData.data[i + 3] = 127;
                    }
                    if ((i / 2 + this.value) % this.value == 0) {
                        imgData.data[i] = 255;
                    }
                    if ((i / 3) % this.value == 0) {
                        imgData.data[i + 1] = 255;
                    }
                    if ((i / 2) % this.value == 1) {
                        imgData.data[i + 2] = 255;
                    }
                }
                this.store.ctx.main.putImageData(imgData, 0, 0);
                render();
                loaded();
            }
        }
    });

    return {
        name: "Sine",
        controls: controls,
        image: "/assets/images/filters/sine.jpg"
    }
});