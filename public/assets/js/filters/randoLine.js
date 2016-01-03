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
                var imgData = this.store.ctx.safe.getImageData(0, 0, width, height);
                var area = width;
                for (var i = 0; i < imgData.data.length; i += 4) {
                    var x = Math.ceil(Math.random() * area);
                    imgData.data[i] = imgData.data[x];
                    imgData.data[i + 1] = imgData.data[x + 1];
                    imgData.data[i + 2] = imgData.data[x + 2];
                }
                this.store.ctx.main.putImageData(imgData, 0, 0);
                render();
                loaded();
            }
        }
    });

    return {
        name: "randoLine",
        controls: controls,
        image: "/assets/images/filters/randoLine.jpg"
    }
});