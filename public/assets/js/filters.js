define([
        'require',
        'filters/ebit',
        'filters/tone',
        'filters/noise',
        'filters/layer',
        'filters/sin',
        'filters/rcv',
        'filters/binary',
        'filters/intensity',
        'filters/cmyk',
        'filters/revCmyk',
        'filters/invert',
        'filters/newConv',
        'filters/brightness',
        'filters/blurF',
        'filters/contrast',
        'filters/reduce',
        'filters/jpgQ',
        'filters/bright',
        //'filters/sort',
        'filters/lineFlip',
        'filters/switchr',
        'filters/rando',
        'filters/randoLine'
    ],
    function (require) {
        var Vue = require('vue');

        var filters = {
            ebit: require('filters/ebit'),
            tone: require('filters/tone'),
            noise: require('filters/noise'),
            layer: require('filters/layer'),
            sin: require('filters/sin'),
            rcv: require('filters/rcv'),
            binary: require('filters/binary'),
            intensity: require('filters/intensity'),
            cmyk: require('filters/cmyk'),
            revCmyk: require('filters/revCmyk'),
            invert: require('filters/invert'),
            newConv: require('filters/newConv'),
            brightness: require('filters/brightness'),
            blurF: require('filters/blurF'),
            contrast: require('filters/contrast'),
            reduce: require('filters/reduce'),
            jpgQ: require('filters/jpgQ'),
            bright: require('filters/bright'),
            lineFlip: require('filters/lineFlip'),
            switchr: require('filters/switchr'),
            rando: require('filters/rando'),
            randoLine: require('filters/randoLine')
        };

        new Vue({
            'el': '#filters',
            data: {
                filters: filters,
                store: store
            },
            template: ' <div id="filters"><span v-for="filter in filters" v-on:click="loadFilter(filter)"> <img v-bind:src="filter.image"/>{{ filter.name }}</span></div>',
            methods: {
                loadFilter: function (filter) {
                    save();
                    this.store.currentFilter = filter;
                    this.store.currentFilter.component = new filter.controls;
                }
            }
        });
    });