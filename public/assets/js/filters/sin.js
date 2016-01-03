define(function (require) {
    'use strict';
    var Vue = require('vue');

    var controls = Vue.extend({
        el: function () {
            return '#filter_controls'
        },
        template: '<span id="filter_controls"><input v-model="value" type="range" min="3" max="27"></span>',
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
                var s, s2;
                for (var i = 0; i < imgData.data.length; i += 3 * Math.ceil(this.value)) {
                    s = Math.floor(Math.sin(i * this.value + i) * 128 + 128);
                    s2 = Math.floor(Math.sin(i * this.value) * 128 + 128);
                    imgData.data[i] = s;
                    imgData.data[i + 1] = s2;
                }
                this.store.ctx.main.putImageData(imgData, 0, 0);
                render();
                loaded();
            }
        }
    });

    return {
        name: "sin",
        controls: controls,
        image: "/assets/images/filters/sin.jpg"
    }
});