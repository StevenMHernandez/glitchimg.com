define(function (require) {
    'use strict';
    var Vue = require('vue');

    var controls = Vue.extend({
        el: function () {
            return '#filter_controls'
        },
        template: '<span id="filter_controls">no variables.</span>',
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
                for (var i = 0; i < imgData.data.length; i += 4) {
                    if (!imgData.data[i - 4] || i % (width * 4) == 1) {
                        imgData.data[i] = (imgData.data[i] * (imgData.data[i] * 3) * imgData.data[i + 4]) / 5;
                        imgData.data[i + 1] = (imgData.data[i] * (imgData.data[i] * 3) * imgData.data[i + 5]) / 5;
                        imgData.data[i + 2] = (imgData.data[i] * (imgData.data[i] * 3) * imgData.data[i + 6]) / 5;
                    }
                    else if (!imgData.data[i + 4] || i % (width * 4) == 0) {
                        imgData.data[i] = (imgData.data[i - 4] * (imgData.data[i] * 3) * imgData.data[i]) / 5;
                        imgData.data[i + 1] = (imgData.data[i - 3] * (imgData.data[i] * 3) * imgData.data[i]) / 5;
                        imgData.data[i + 2] = (imgData.data[i - 2] * (imgData.data[i] * 3) * imgData.data[i]) / 5;
                    }
                    else {
                        imgData.data[i] = (imgData.data[i - 4] * (imgData.data[i] * 3) * imgData.data[i + 4]) / 5;
                        imgData.data[i + 1] = (imgData.data[i - 3] * (imgData.data[i] * 3) * imgData.data[i + 5]) / 5;
                        imgData.data[i + 2] = (imgData.data[i - 2] * (imgData.data[i] * 3) * imgData.data[i + 6]) / 5;
                    }
                }
                this.store.ctx.main.putImageData(imgData, 0, 0);
                render();
            }
        }
    });

    return {
        name: "lcon",
        controls: controls,
        image: "/assets/images/filters/lcon.jpg"
    }
});