define(function (require) {
    'use strict';
    var Vue = require('vue');

    var controls = Vue.extend({
        el: function () {
            return '#filter_controls'
        },
        template: '<span id="filter_controls"><input v-model="offset" type="range" min="0" max="{{max()}}" ></span>',
        data: function () {
            return {
                store: store,
                offset: random(0, this.max())
            }
        },
        watch: {
            offset: 'render'
        },
        attached: function () {
            this.render();
        },
        methods: {
            max: function () {
                console.log(height >= width ? height : width);
                return height >= width ? height : width;
            },
            render: function () {
                loading();
                this.store.ctx.main.globalAlpha = .125;
                this.store.ctx.main.drawImage(this.store.ctx.safe.canvas, -this.offset, -this.offset);
                this.store.ctx.main.drawImage(this.store.ctx.safe.canvas, 0, -this.offset);
                this.store.ctx.main.drawImage(this.store.ctx.safe.canvas, this.offset, -this.offset);
                this.store.ctx.main.drawImage(this.store.ctx.safe.canvas, -this.offset, 0);
                this.store.ctx.main.drawImage(this.store.ctx.safe.canvas, this.offset, 0);
                this.store.ctx.main.drawImage(this.store.ctx.safe.canvas, -this.offset, this.offset);
                this.store.ctx.main.drawImage(this.store.ctx.safe.canvas, 0, this.offset);
                this.store.ctx.main.drawImage(this.store.ctx.safe.canvas, this.offset, this.offset);
                this.store.ctx.main.globalAlpha = 1;
                render();
                loaded();
            }
        }
    });

    return {
        name: "False Blur",
        controls: controls,
        image: "/assets/images/filters/blurF.jpg"
    }
});