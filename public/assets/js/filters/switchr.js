define(function (require) {
    'use strict';
    var Vue = require('vue');

    var controls = Vue.extend({
        el: function () {
            return '#filter_controls'
        },
        template: '<span id="filter_controls">no variables</span>',
        data: function () {
            return {
                store: store
            }
        },
        attached: function () {
            this.render();
        },
        methods: {
            render: function () {
                loading();
                var current_i = 0;
                var imgData = this.store.ctx.safe.getImageData(0, 0, width, height);
                for (var i = 0; i < imgData.data.length; i += 3) {
                    imgData.data[i] = imgData.data[current_i];
                    imgData.data[i + 1] = imgData.data[current_i + 1];
                    imgData.data[i + 2] = imgData.data[current_i + 2];
                    if (imgData.data[i] > 128) {
                        current_i += 3;
                    }
                    else if (imgData.data[i] <= 128) {
                        current_i += 4;
                    }
                    else {
                        current_i += 0;
                    }
                }
                this.store.ctx.main.putImageData(imgData, 0, 0);
                render();
                loaded();
            }
        }
    });

    return {
        name: "Switchr",
        controls: controls,
        image: "/assets/images/filters/switchr.jpg"
    }
});