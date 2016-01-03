define(function (require) {
    'use strict';
    var Vue = require('vue');

    var controls = Vue.extend({
        el: function () {
            return '#filter_controls'
        },
        template: '<span id="filter_controls"><input v-model="pixelSize" id = "range" type = "range" name = "points" min = "16" max="128"></span>',
        data: function () {
            return {
                name: "test",
                store: store,
                pixelSize: random(16, 128)
            }
        },
        watch: {
            pixelSize: 'render'
        },
        attached: function () {
            this.render();
        },
        methods: {
            render: function () {
                loading();

                if (width >= height) {
                    this.store.ctx.empty.canvas.width = this.pixelSize;
                    this.store.ctx.empty.canvas.height = (this.pixelSize / width) * height;
                }
                else {
                    this.store.ctx.empty.canvas.height = this.pixelSize;
                    this.store.ctx.empty.canvas.width = (this.pixelSize / height) * width;
                }
                this.store.ctx.empty.drawImage(this.store.ctx.safe.canvas, 0, 0, this.store.ctx.empty.canvas.width, this.store.ctx.empty.canvas.height);
                this.store.ctx.main.imageSmoothingEnabled = false;
                this.store.ctx.main.webkitImageSmoothingEnabled = false;
                this.store.ctx.main.mozImageSmoothingEnabled = false;
                this.store.ctx.main.clearRect(0, 0, width, height);
                this.store.ctx.main.drawImage(this.store.ctx.empty.canvas, 0, 0, width, height);

                render();
                loaded();
            }
        }
    });

    return {
        name: "8-bit",
        controls: controls,
        image: "/assets/images/filters/8bit.jpg"
    }
});