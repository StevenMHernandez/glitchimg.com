define(function (require) {
    'use strict';
    var Vue = require('vue');

    var controls = Vue.extend({
        el: function () {
            return '#filter_controls'
        },
        template: '<div id="filter_controls">' +
        'Keep-BG:<input v-model="background" type="checkbox"> ' +
        '<select v-model="wave"> <option v-for="waveform in waveforms" value="{{$key}}"> {{waveform}} </option></select><br/>' +
        'Direction : <select v-model="axis"> <option value="X" > Horizontal </option> <option value="Y" > Vertical </option> </select><br/>' +
        'Frequency : <input type="number" class="smallInput" v-model="frequency">' +
        'Amplitude : <input type="number" class="smallInput" v-model="amplitude"> px <br/>' +
        'Shift:<input type="range" v-model="phase" max="{{axis == \'X\' ? store.max : 10}}">' +
        '</div>',
        data: function () {
            return {
                store: store,
                background: true,
                wave: "sine",
                waveforms: {
                    sine: "Sine Wave",
                    square: "Square Wave",
                    triangle: "Triangle Wave",
                    saw: "Saw Wave",
                    random: "Random Block Wave"
                },
                period: 9,
                amplitude: random(1, 100),
                frequency: random(1, 100),
                phase: random(0, 100),
                axis: "X"
            }
        },
        methods: {
            render: function () {
                loading();

                var t, i, shift, a;
                var values = this;

                if (!this.background) {
                    this.store.ctx.main.fillStyle = "#FFFFFF";
                    this.store.ctx.main.fillRect(0, 0, width, height);
                }
                else {
                    this.store.ctx.main.drawImage(this.store.ctx.safe.canvas, 0, 0);
                }

                // render waveform
                if (this.wave == 'sine') {
                    if (values.axis == 'X') {
                        for (i = 0; i < width; i++) {
                            t = i + values.phase;
                            shift = values.amplitude * Math.sin(2 * Math.PI * values.frequency * (t / width));
                            this.store.ctx.main.drawImage(this.store.ctx.safe.canvas, i, 0, 1, height, i, shift, 1, height);
                        }
                    }
                    else {
                        for (i = 0; i < height; i++) {
                            t = i + values.phase;
                            shift = values.amplitude * Math.sin(2 * Math.PI * values.frequency * (t / width));
                            this.store.ctx.main.drawImage(this.store.ctx.safe.canvas, 0, i, width, 1, shift, i, width, 1);
                        }
                    }
                }
                if (this.wave == 'saw') {
                    if (values.axis == 'X') {
                        for (i = 0; i < width; i++) {
                            t = i - values.phase;
                            shift = values.amplitude * 2 * ((t / this.period) - Math.floor(.5 + (t / this.period)));
                            this.store.ctx.main.drawImage(this.store.ctx.safe.canvas, i, 0, 1, height, i, shift, 1, height);
                        }
                    }
                    else {
                        for (i = 0; i < height; i++) {
                            t = i - values.phase;
                            shift = values.amplitude * 2 * ((t / this.period) - Math.floor(.5 + (t / this.period)));
                            this.store.ctx.main.drawImage(this.store.ctx.safe.canvas, 0, i, width, 1, shift, i, width, 1);

                        }
                    }
                }
                if (this.wave == 'triangle') {
                    if (values.axis == 'X') {
                        for (i = 0; i < width; i++) {
                            t = i - values.phase;
                            a = this.period;
                            shift = values.amplitude * ((2 / a) * (t - a * Math.floor((t / a) + .5))) * Math.pow(-1, Math.floor((t / a) + .5));
                            this.store.ctx.main.drawImage(this.store.ctx.safe.canvas, i, 0, 1, height, i, shift, 1, height);
                        }
                    }
                    else {
                        for (i = 0; i < height; i++) {
                            t = i - values.phase;
                            a = this.period;
                            shift = values.amplitude * ((2 / a) * (t - a * Math.floor((t / a) + .5))) * Math.pow(-1, Math.floor((t / a) + .5));
                            this.store.ctx.main.drawImage(this.store.ctx.safe.canvas, 0, i, width, 1, shift, i, width, 1);

                        }
                    }
                }
                if (this.wave == 'square') {
                    if (values.axis == 'X') {
                        for (i = 0; i < width; i++) {
                            t = i - values.phase;
                            shift = Math.sin(t / this.period * 3);
                            if (shift > 0) {
                                shift = values.amplitude;
                            }
                            else if (shift < 0) {
                                shift = -values.amplitude;
                            }
                            else {
                                shift = 0;
                            }
                            this.store.ctx.main.drawImage(this.store.ctx.safe.canvas, i, 0, 1, height, i, shift, 1, height);
                        }
                    }
                    else {
                        for (i = 0; i < height; i++) {
                            t = i - values.phase;
                            shift = Math.sin(t / this.period * 3);
                            if (shift > 0) {
                                shift = values.amplitude;
                            }
                            else if (shift < 0) {
                                shift = -values.amplitude;
                            }
                            else {
                                shift = 0;
                            }
                            this.store.ctx.main.drawImage(this.store.ctx.safe.canvas, 0, i, width, 1, shift, i, width, 1);
                        }
                    }
                }
                if (this.wave == 'random') {
                    var random = Math.round((Math.random() * (2 * values.amplitude)) - values.amplitude);
                    var shiftSign = 0;
                    if (values.axis == 'X') {
                        for (i = 0; i < width; i++) {
                            shift = Math.sin(i / this.period * 3);

                            if (shift > 0 && shiftSign == 0) {
                                random = Math.round((Math.random() * (2 * values.amplitude)) - values.amplitude);
                                shiftSign = 1;
                            } else if (shift < 0 && shiftSign == 1) {
                                random = Math.round((Math.random() * (2 * values.amplitude)) - values.amplitude);
                                shiftSign = 0;
                            }

                            this.store.ctx.main.drawImage(this.store.ctx.safe.canvas, i, 0, 1, height, i, random, 1, height);
                        }
                    }
                    else {
                        for (i = 0; i < height; i++) {
                            shift = Math.sin(i / this.period * 3);

                            if (shift > 0 && shiftSign == 0) {
                                random = Math.round((Math.random() * (2 * values.amplitude)) - values.amplitude);
                                shiftSign = 1;
                            } else if (shift < 0 && shiftSign == 1) {
                                random = Math.round((Math.random() * (2 * values.amplitude)) - values.amplitude);
                                shiftSign = 0;
                            }

//                    this.store.ctx.main.drawImage(this.store.ctx.safe.canvas, i, 0, 1, height, i, random, 1, height);
                            this.store.ctx.main.drawImage(this.store.ctx.safe.canvas, 0, i, width, 1, random, i, width, 1);

                        }
                    }
                }
                render();
                loaded();
            }
        },
        watch: {
            entropy: 'render',
            background: 'render',
            wave: 'render',
            period: 'render',
            amplitude: 'render',
            frequency: 'render',
            phase: 'render',
            axis: 'render'
        },
        attached: function () {
            this.render();
        },
        destroy: function () {
            console.log('noise destroyed');
        }
    });

    return {
        name: "Tone",
        controls: controls,
        image: "/assets/images/filters/tone.jpg"
    }
});