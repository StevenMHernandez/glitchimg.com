define(function (require) {
    'use strict';
    var Vue = require('vue');

    var controls = Vue.extend({
        el: function () {
            return '#filter_controls'
        },
        template: '<span id="filter_controls"><select v-model="layer_style"><option v-for="style in styles" value="{{$key}}" > {{ style }} </option></select></span>',
        data: function () {
            return {
                store: store,
                layer_style: "normal",
                styles: {
                    normal: "Select an Options",
                    screen: "Screen",
                    multiply: "Multiply",
                    difference: "Difference",
                    exclusion: "Exclusion",
                    add: "Linear Dodge",
                    lighten: "Lighten",
                    darken: "Darken",
                    overlay: "Overlay",
                    hardlight: "Hard Light",
                    colordodge: "Color Dodge",
                    colorburn: "Color Burn"
                }
            }
        },
        watch: {
            layer_style: 'render'
        },
        attached: function () {
            this.render();
        },
        methods: {
            render: function () {
                loading();
                if (this.layer_style == 'normal') {
                    this.store.ctx.main.drawImage(this.store.ctx.safe.canvas, 0, 0);
                }
                else {
                    loading();
                    this.store.ctx.main.drawImage(this.store.ctx.safe.canvas, 0, 0);
                    this.store.ctx.original.blendOnto(this.store.ctx.main, this.layer_style);
                }
                render();
                loaded();
            }
        }
    });

    return {
        name: "Layer",
        controls: controls,
        image: "/assets/images/filters/layer.jpg"
    }
});