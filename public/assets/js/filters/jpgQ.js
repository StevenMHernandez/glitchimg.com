define(function (require) {
    'use strict';
    var Vue = require('vue');

    var controls = Vue.extend({
        el: function () {
            return '#filter_controls'
        },
        template: '<span id="filter_controls"><input v-model="quality" type="range" value="0" min="2" max="20"></span>',
        data: function () {
            return {
                store: store,
                quality: random(16, 128)
            }
        },
        watch: {
            quality: 'render'
        },
        attached: function () {
            this.render();
        },
        methods: {
            render: function () {
                loading();
                var quality = this.quality * .01;

                this.store.ctx.main.drawImage(this.store.ctx.safe.canvas, 0, 0);

                var linkP = this.store.ctx.main.canvas.toDataURL('image/jpeg', quality);
                var base64 = new Image();
                base64.src = linkP;
                var parent = this;
                base64.onload = function () {
                    parent.store.ctx.main.drawImage(base64, 0, 0, width, height);
                    render();
                };
                loaded();
            }
        }
    });

    return {
        name: "jpgQuality",
        controls: controls,
        image: "/assets/images/filters/jpgQ.jpg"
    }
});