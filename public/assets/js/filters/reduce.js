define(function (require) {
    'use strict';
    var Vue = require('vue');

    var controls = Vue.extend({
        el: function () {
            return '#filter_controls'
        },
        template: '<span id="filter_controls"><input v-model="levels" type="range" min="1" max="255"></span>',
        data: function () {
            return {
                store: store,
                levels: random(1, 255)
            }
        },
        watch: {
            levels: 'render'
        },
        attached: function () {
            this.render();
        },
        methods: {
            render: function () {
                loading();
                var imgData = this.store.ctx.safe.getImageData(0, 0, width, height);
                for (var i = 0; i < imgData.data.length; i += 4) {
                    imgData.data[i] = Math.round(imgData.data[i] / this.levels) * this.levels;
                    imgData.data[i + 1] = Math.round(imgData.data[i + 1] / this.levels) * this.levels;
                    imgData.data[i + 2] = Math.round(imgData.data[i + 2] / this.levels) * this.levels;
                }
                this.store.ctx.main.putImageData(imgData, 0, 0);
                render();
                loaded();
            }
        }
    });

    return {
        name: "Reduce",
        controls: controls,
        image: "/assets/images/filters/reduce.jpg"
    }
});