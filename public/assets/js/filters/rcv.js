define(function (require) {
    'use strict';
    var Vue = require('vue');

    var controls = Vue.extend({
        el: function () {
            return '#filter_controls'
        },
        template: '<span id="filter_controls"><input v-model="value" type="range" min="4" max="255"></span>',
        data: function () {
            return {
                store: store,
                value: random(4, 255)
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
                for (var i = 0; i < imgData.data.length; i += 3 * Math.ceil(this.value / 100)) {
                    if (imgData.data[i] == this.value) {
                        imgData.data[i] = imgData.data[i + this.value];
                    }
                    else if (imgData.data[i] > this.value) {
                        imgData.data[i] = imgData.data[i + this.value];
                    }
                    else {
                        imgData.data[i] = imgData.data[i - this.value];
                    }
                }
                this.store.ctx.main.putImageData(imgData, 0, 0);
                render();
                loaded();
            }
        }
    });

    return {
        name: "rcv",
        controls: controls,
        image: "/assets/images/filters/rcv.jpg"
    }
});