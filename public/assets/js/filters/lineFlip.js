define(function (require) {
    'use strict';
    var Vue = require('vue');

    var controls = Vue.extend({
        el: function () {
            return '#filter_controls'
        },
        template: '<span id="filter_controls">Line size: <input type="number" min=1 v-model="line_size">px.</span>',
        data: function () {
            return {
                store: store,
                line_size: random(1, 100)
            }
        },
        watch: {
            line_size: 'render'
        },
        attached: function () {
            this.render();
        },
        methods: {
            render: function () {
                if (isInt(this.line_size)) {
                    var line_size = parseInt(this.line_size);
                    loading();
                    for (var i = 0; i < height; i += line_size) {
                        this.store.ctx.main.translate(width, 0);
                        this.store.ctx.main.scale(-1, 1);
                        this.store.ctx.main.drawImage(this.store.ctx.safe.canvas, 0, i, width, line_size, 0, i, width, line_size);
                    }
                    loaded();
                    render();
                }
            }
        }
    });

    return {
        name: "lineFlip",
        controls: controls,
        image: "/assets/images/filters/lineFlip.jpg"
    }
});