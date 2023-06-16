/*
 * MelonJS Game Engine
 * Copyright (C) 2011 - 2018 Olivier Biot
 * http://www.melonjs.org
 *
 * a simple debug panel plugin
 * usage : me.plugin.register.defer(this, me.debug.Panel, "debug");
 *
 * you can then use me.plugins.debug.show() or me.plugins.debug.hide()
 * to show or hide the panel, press the "s" key.
 * default key can be configured using the following parameters in the url :
 * e.g. http://myURL/index.html#debugToggleKey=d
 *
 * note :
 * Heap Memory information is available under Chrome when using
 * the "--enable-memory-info" parameter to launch Chrome
 */

(function () {

    // ensure that me.debug is defined
    me.debug = me.debug || {};

    var DEBUG_HEIGHT = 50;

    var Counters = me.Object.extend({
        init : function (stats) {
            this.stats = {};
            this.reset(stats);
        },

        reset : function (stats) {
            var self = this;
            (stats || Object.keys(this.stats)).forEach(function (stat) {
                self.stats[stat] = 0;
            });
        },

        inc : function (stat, value) {
            this.stats[stat] += (value || 1);
        },

        get : function (stat) {
            return this.stats[stat];
        }
    });


    var fontDataSource =
            "info face=Mapix size=10 bold=0 italic=0 charset= unicode= stretchH=100 smooth=1 aa=1 padding=5,5,5,5 spacing=0,0 outline=0\n" +
            "common lineHeight=15 base=12 scaleW=1024 scaleH=64 pages=1 packed=0\n" +
            "page id=0 file=Mapix.png\n" +
            "chars count=134\n" +
            "char id=33 x=5 y=5 width=2 height=9 xoffset=0 yoffset=3 xadvance=3 page=0 chnl=15\n" +
            "char id=34 x=12 y=5 width=4 height=3 xoffset=0 yoffset=3 xadvance=5 page=0 chnl=15\n" +
            "char id=35 x=21 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=36 x=33 y=5 width=7 height=12 xoffset=0 yoffset=1 xadvance=8 page=0 chnl=15\n" +
            "char id=37 x=45 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=38 x=57 y=5 width=8 height=9 xoffset=0 yoffset=3 xadvance=9 page=0 chnl=15\n" +
            "char id=39 x=70 y=5 width=2 height=3 xoffset=0 yoffset=3 xadvance=3 page=0 chnl=15\n" +
            "char id=40 x=77 y=5 width=3 height=9 xoffset=0 yoffset=3 xadvance=4 page=0 chnl=15\n" +
            "char id=41 x=85 y=5 width=3 height=9 xoffset=0 yoffset=3 xadvance=4 page=0 chnl=15\n" +
            "char id=42 x=93 y=5 width=4 height=4 xoffset=1 yoffset=5 xadvance=6 page=0 chnl=15\n" +
            "char id=43 x=102 y=5 width=7 height=7 xoffset=0 yoffset=4 xadvance=8 page=0 chnl=15\n" +
            "char id=44 x=114 y=5 width=2 height=3 xoffset=0 yoffset=10 xadvance=3 page=0 chnl=15\n" +
            "char id=45 x=121 y=5 width=7 height=2 xoffset=0 yoffset=6 xadvance=8 page=0 chnl=15\n" +
            "char id=46 x=133 y=5 width=2 height=2 xoffset=0 yoffset=10 xadvance=3 page=0 chnl=15\n" +
            "char id=47 x=140 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=48 x=152 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=49 x=164 y=5 width=4 height=9 xoffset=0 yoffset=3 xadvance=5 page=0 chnl=15\n" +
            "char id=50 x=173 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=51 x=185 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=52 x=197 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=53 x=209 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=54 x=221 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=55 x=233 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=56 x=245 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=57 x=257 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=58 x=269 y=5 width=2 height=7 xoffset=0 yoffset=5 xadvance=3 page=0 chnl=15\n" +
            "char id=59 x=276 y=5 width=2 height=8 xoffset=0 yoffset=5 xadvance=3 page=0 chnl=15\n" +
            "char id=60 x=283 y=5 width=5 height=9 xoffset=0 yoffset=3 xadvance=6 page=0 chnl=15\n" +
            "char id=61 x=293 y=5 width=7 height=4 xoffset=0 yoffset=5 xadvance=8 page=0 chnl=15\n" +
            "char id=62 x=305 y=5 width=5 height=9 xoffset=0 yoffset=3 xadvance=6 page=0 chnl=15\n" +
            "char id=63 x=315 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=64 x=327 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=65 x=339 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=66 x=351 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=67 x=363 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=68 x=375 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=69 x=387 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=70 x=399 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=71 x=411 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=72 x=423 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=73 x=435 y=5 width=4 height=9 xoffset=0 yoffset=3 xadvance=5 page=0 chnl=15\n" +
            "char id=74 x=444 y=5 width=4 height=9 xoffset=0 yoffset=3 xadvance=5 page=0 chnl=15\n" +
            "char id=75 x=453 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=76 x=465 y=5 width=5 height=9 xoffset=0 yoffset=3 xadvance=6 page=0 chnl=15\n" +
            "char id=77 x=475 y=5 width=9 height=9 xoffset=0 yoffset=3 xadvance=10 page=0 chnl=15\n" +
            "char id=78 x=489 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=79 x=501 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=80 x=513 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=81 x=525 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=82 x=537 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=83 x=549 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=84 x=561 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=85 x=573 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=86 x=585 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=87 x=597 y=5 width=9 height=9 xoffset=0 yoffset=3 xadvance=10 page=0 chnl=15\n" +
            "char id=88 x=611 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=89 x=623 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=90 x=635 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=91 x=647 y=5 width=3 height=9 xoffset=0 yoffset=3 xadvance=4 page=0 chnl=15\n" +
            "char id=92 x=655 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=93 x=667 y=5 width=3 height=9 xoffset=0 yoffset=3 xadvance=4 page=0 chnl=15\n" +
            "char id=94 x=675 y=5 width=7 height=4 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=95 x=687 y=5 width=7 height=2 xoffset=0 yoffset=10 xadvance=8 page=0 chnl=15\n" +
            "char id=96 x=699 y=5 width=2 height=3 xoffset=5 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=97 x=706 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=98 x=718 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=99 x=730 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=100 x=742 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=101 x=754 y=5 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=102 x=121 y=12 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=103 x=687 y=12 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=104 x=293 y=14 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=105 x=12 y=13 width=4 height=9 xoffset=0 yoffset=3 xadvance=5 page=0 chnl=15\n" +
            "char id=106 x=93 y=14 width=4 height=9 xoffset=0 yoffset=3 xadvance=5 page=0 chnl=15\n" +
            "char id=107 x=675 y=14 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=108 x=102 y=17 width=5 height=9 xoffset=0 yoffset=3 xadvance=6 page=0 chnl=15\n" +
            "char id=109 x=269 y=18 width=9 height=9 xoffset=0 yoffset=3 xadvance=10 page=0 chnl=15\n" +
            "char id=110 x=21 y=19 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=111 x=45 y=19 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=112 x=57 y=19 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=113 x=69 y=19 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=114 x=81 y=19 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=115 x=133 y=19 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=116 x=145 y=19 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=117 x=157 y=19 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=118 x=169 y=19 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=119 x=181 y=19 width=9 height=9 xoffset=0 yoffset=3 xadvance=10 page=0 chnl=15\n" +
            "char id=120 x=195 y=19 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=121 x=207 y=19 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=122 x=219 y=19 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=123 x=112 y=17 width=4 height=9 xoffset=0 yoffset=3 xadvance=5 page=0 chnl=15\n" +
            "char id=124 x=699 y=13 width=2 height=9 xoffset=0 yoffset=3 xadvance=3 page=0 chnl=15\n" +
            "char id=125 x=231 y=19 width=4 height=9 xoffset=0 yoffset=3 xadvance=5 page=0 chnl=15\n" +
            "char id=126 x=240 y=19 width=8 height=3 xoffset=0 yoffset=5 xadvance=9 page=0 chnl=15\n" +
            "char id=183 x=133 y=12 width=2 height=2 xoffset=0 yoffset=6 xadvance=3 page=0 chnl=15\n" +
            "char id=184 x=253 y=19 width=3 height=4 xoffset=0 yoffset=10 xadvance=4 page=0 chnl=15\n" +
            "char id=192 x=305 y=19 width=7 height=12 xoffset=0 yoffset=0 xadvance=8 page=0 chnl=15\n" +
            "char id=193 x=317 y=19 width=7 height=12 xoffset=0 yoffset=0 xadvance=8 page=0 chnl=15\n" +
            "char id=194 x=329 y=19 width=7 height=12 xoffset=0 yoffset=0 xadvance=8 page=0 chnl=15\n" +
            "char id=195 x=341 y=19 width=7 height=12 xoffset=0 yoffset=0 xadvance=8 page=0 chnl=15\n" +
            "char id=196 x=353 y=19 width=7 height=12 xoffset=0 yoffset=0 xadvance=8 page=0 chnl=15\n" +
            "char id=197 x=365 y=19 width=7 height=12 xoffset=0 yoffset=0 xadvance=8 page=0 chnl=15\n" +
            "char id=198 x=377 y=19 width=10 height=9 xoffset=0 yoffset=3 xadvance=11 page=0 chnl=15\n" +
            "char id=199 x=392 y=19 width=7 height=13 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=200 x=404 y=19 width=7 height=12 xoffset=0 yoffset=0 xadvance=8 page=0 chnl=15\n" +
            "char id=201 x=416 y=19 width=7 height=12 xoffset=0 yoffset=0 xadvance=8 page=0 chnl=15\n" +
            "char id=202 x=428 y=19 width=7 height=12 xoffset=0 yoffset=0 xadvance=8 page=0 chnl=15\n" +
            "char id=203 x=440 y=19 width=7 height=12 xoffset=0 yoffset=0 xadvance=8 page=0 chnl=15\n" +
            "char id=204 x=283 y=19 width=4 height=12 xoffset=0 yoffset=0 xadvance=5 page=0 chnl=15\n" +
            "char id=205 x=452 y=19 width=4 height=12 xoffset=0 yoffset=0 xadvance=5 page=0 chnl=15\n" +
            "char id=206 x=461 y=19 width=4 height=12 xoffset=0 yoffset=0 xadvance=5 page=0 chnl=15\n" +
            "char id=207 x=470 y=19 width=4 height=12 xoffset=0 yoffset=0 xadvance=5 page=0 chnl=15\n" +
            "char id=208 x=479 y=19 width=8 height=9 xoffset=-1 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=209 x=492 y=19 width=7 height=12 xoffset=0 yoffset=0 xadvance=8 page=0 chnl=15\n" +
            "char id=210 x=504 y=19 width=7 height=12 xoffset=0 yoffset=0 xadvance=8 page=0 chnl=15\n" +
            "char id=211 x=516 y=19 width=7 height=12 xoffset=0 yoffset=0 xadvance=8 page=0 chnl=15\n" +
            "char id=212 x=528 y=19 width=7 height=12 xoffset=0 yoffset=0 xadvance=8 page=0 chnl=15\n" +
            "char id=213 x=540 y=19 width=7 height=12 xoffset=0 yoffset=0 xadvance=8 page=0 chnl=15\n" +
            "char id=214 x=552 y=19 width=7 height=12 xoffset=0 yoffset=0 xadvance=8 page=0 chnl=15\n" +
            "char id=215 x=564 y=19 width=7 height=7 xoffset=0 yoffset=4 xadvance=8 page=0 chnl=15\n" +
            "char id=216 x=576 y=19 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=217 x=588 y=19 width=7 height=12 xoffset=0 yoffset=0 xadvance=8 page=0 chnl=15\n" +
            "char id=218 x=600 y=19 width=7 height=12 xoffset=0 yoffset=0 xadvance=8 page=0 chnl=15\n" +
            "char id=219 x=612 y=19 width=7 height=12 xoffset=0 yoffset=0 xadvance=8 page=0 chnl=15\n" +
            "char id=220 x=624 y=19 width=7 height=10 xoffset=0 yoffset=1 xadvance=8 page=0 chnl=15\n" +
            "char id=7838 x=636 y=19 width=7 height=9 xoffset=0 yoffset=3 xadvance=8 page=0 chnl=15\n" +
            "char id=8212 x=648 y=19 width=8 height=2 xoffset=0 yoffset=6 xadvance=9 page=0 chnl=15\n" +
            "char id=8216 x=5 y=19 width=2 height=3 xoffset=0 yoffset=3 xadvance=3 page=0 chnl=15\n" +
            "char id=8217 x=261 y=19 width=2 height=3 xoffset=0 yoffset=3 xadvance=3 page=0 chnl=15\n" +
            "char id=8220 x=661 y=19 width=4 height=3 xoffset=0 yoffset=3 xadvance=5 page=0 chnl=15\n" +
            "char id=8221 x=706 y=19 width=4 height=3 xoffset=0 yoffset=3 xadvance=5 page=0 chnl=15\n" +
            "char id=8230 x=715 y=19 width=7 height=2 xoffset=0 yoffset=10 xadvance=8 page=0 chnl=15\n" +
            "char id=8249 x=727 y=19 width=4 height=7 xoffset=0 yoffset=4 xadvance=5 page=0 chnl=15\n" +
            "char id=8250 x=736 y=19 width=4 height=7 xoffset=0 yoffset=4 xadvance=5 page=0 chnl=15\n" +
            "char id=32 x=0 y=0 width=0 height=0 xoffset=0 yoffset=4 xadvance=3 page=0 chnl=15";

    var fontImageSource = "data:image/png;base64,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";

    var DebugPanel = me.Renderable.extend({
        /** @private */
        init : function (debugToggle) {
            // call the super constructor
            this._super(me.Renderable, "init", [ 0, 0, me.game.viewport.width, DEBUG_HEIGHT ]);

            // enable collision and event detection
            this.isKinematic = false;

            // minimum melonJS version expected
            this.version = "5.1.0";

            // to hold the debug options
            // clickable rect area
            this.area = {};

            // Useful counters
            this.counters = new Counters([
                "shapes",
                "sprites",
                "velocity",
                "bounds",
                "children"
            ]);

            // for z ordering
            // make it ridiculously high
            this.pos.z = Infinity;

            // visibility flag
            this.visible = false;

            // frame update time in ms
            this.frameUpdateTime = 0;

            // frame draw time in ms
            this.frameDrawTime = 0;

            // set the object GUID value
            this.GUID = "debug-" + me.utils.createGUID();

            // set the object entity name
            this.name = "me.debugPanel";

            // persistent
            this.isPersistent = true;

            // a floating object
            this.floating = true;

            // renderable
            this.isRenderable = true;

            // always update, even when not visible
            this.alwaysUpdate = true;

            // WebGL/Canvas compatibility
            this.canvas = me.video.createCanvas(this.width, this.height, true);

            // create a default font, with fixed char width
            this.font_size = 10;
            this.mod = 2;
            if (this.width < 500) {
                this.font_size = 7;
                this.mod = this.mod * (this.font_size / 10);
            }

            // create the bitmapfont
            var fontImage = new Image();
            fontImage.src = fontImageSource;

            this.font = new me.BitmapFont(fontDataSource, fontImage);

            // free static ressources
            fontImageSource = null;
            fontDataSource = null;

            // clickable areas
            var size = 10 * this.mod;
            this.area.renderHitBox   = new me.Rect(250, 2,  size, size);
            this.area.renderVelocity = new me.Rect(250, 17, size, size);
            this.area.renderQuadTree = new me.Rect(410, 2,  size, size);

            // enable the FPS counter
            me.debug.displayFPS = true;

            var self = this;

            // add some keyboard shortcuts
            this.debugToggle = debugToggle || me.input.KEY.S;
            this.keyHandler = me.event.subscribe(me.event.KEYDOWN, function (action, keyCode) {
                if (keyCode === self.debugToggle) {
                    me.plugins.debugPanel.toggle();
                }
            });

            // some internal string/length
            this.help_str        = "["+String.fromCharCode(32 + this.debugToggle)+"]show/hide";
            this.help_str_len    = this.font.measureText(me.video.renderer, this.help_str).width;
            this.fps_str_len     = this.font.measureText(me.video.renderer, "00/00 fps").width;
            this.memoryPositionX = 325 * this.mod;

            // resize the panel if the browser is resized
            me.event.subscribe(me.event.VIEWPORT_ONRESIZE, function (w) {
                self.resize(w, DEBUG_HEIGHT);
            });

            //patch patch patch !
            this.patchSystemFn();

            this.anchorPoint.set(0, 0);
        },

        /**
         * patch system fn to draw debug information
         */
        patchSystemFn : function () {

            // add a few new debug flag (if not yet defined)
            me.debug.renderHitBox   = me.debug.renderHitBox   || me.game.HASH.hitbox || false;
            me.debug.renderVelocity = me.debug.renderVelocity || me.game.HASH.velocity || false;
            me.debug.renderQuadTree = me.debug.renderQuadTree || me.game.HASH.quadtree || false;

            var _this = this;
            var bounds = new me.Rect(0, 0, 0, 0);

            // patch timer.js
            me.plugin.patch(me.timer, "update", function (dt) {
                // call the original me.timer.update function
                this._patched(dt);

                // call the FPS counter
                me.timer.countFPS();
            });

            // patch me.game.update
            me.plugin.patch(me.game, "update", function (dt) {
                var frameUpdateStartTime = window.performance.now();

                this._patched(dt);

                // calculate the update time
                _this.frameUpdateTime = window.performance.now() - frameUpdateStartTime;
            });

            // patch me.game.draw
            me.plugin.patch(me.game, "draw", function () {
                var frameDrawStartTime = window.performance.now();

                _this.counters.reset();

                this._patched();

                // calculate the drawing time
                _this.frameDrawTime = window.performance.now() - frameDrawStartTime;
            });

            // patch sprite.js
            me.plugin.patch(me.Sprite, "draw", function (renderer) {

                // call the original me.Sprite.draw function
                this._patched(renderer);

                // don't do anything else if the panel is hidden
                if (_this.visible) {

                    // increment the sprites counter
                    _this.counters.inc("sprites");

                    // draw the sprite rectangle
                    if (me.debug.renderHitBox) {
                        var bounds = this.getBounds();
                        var ax = this.anchorPoint.x * bounds.width,
                            ay = this.anchorPoint.y * bounds.height;

                        // translate back as the bounds position
                        // is already adjusted to the anchor Point
                        renderer.translate(ax, ay);

                        renderer.setColor("green");
                        renderer.drawShape(bounds);

                        renderer.translate(-ax, -ay);

                        if (this.body) {
                            renderer.translate(this.pos.x, this.pos.y);
                            // draw all defined shapes
                            renderer.setColor("red");
                            for (var i = this.body.shapes.length, shape; i--, (shape = this.body.shapes[i]);) {
                                renderer.drawShape(shape);
                                _this.counters.inc("shapes");
                            }
                        }
                    }
                }
            });

            /*
            // patch font.js
            me.plugin.patch(me.Font, "draw", function (renderer, text, x, y) {
                // call the original me.Sprite.draw function
                this._patched(renderer, text, x, y);
                // draw the font rectangle
                if (me.debug.renderHitBox) {
                    renderer.save();
                    renderer.setColor("orange");
                    renderer.drawShape(this.getBounds());
                    _this.counters.inc("bounds");
                    renderer.restore();
                }
            });
            // patch font.js
            me.plugin.patch(me.Font, "drawStroke", function (renderer, text, x, y) {
                // call the original me.Sprite.draw function
                this._patched(renderer, text, x, y);
                // draw the font rectangle
                if (me.debug.renderHitBox) {
                    renderer.save();
                    renderer.setColor("orange");
                    renderer.drawShape(this.getBounds());
                    _this.counters.inc("bounds");
                    renderer.restore();
                }
            });
            */

            // patch entities.js
            me.plugin.patch(me.Entity, "postDraw", function (renderer) {
                // don't do anything else if the panel is hidden
                if (_this.visible) {
                    // increment the bounds counter
                    _this.counters.inc("bounds");

                    // check if debug mode is enabled
                    if (me.debug.renderHitBox) {
                        renderer.save();

                        renderer.translate(
                            -this.pos.x - this.body.pos.x - this.ancestor._absPos.x,
                            -this.pos.y - this.body.pos.y - this.ancestor._absPos.y
                        );

                        if (this.renderable instanceof me.Renderable) {
                            renderer.translate(
                                -this.anchorPoint.x * this.body.width,
                                -this.anchorPoint.y * this.body.height
                            );
                        }

                        // draw the bounding rect shape
                        renderer.setColor("orange");
                        renderer.drawShape(this.getBounds());

                        renderer.translate(
                            this.pos.x + this.ancestor._absPos.x,
                            this.pos.y + this.ancestor._absPos.y
                        );

                        // draw all defined shapes
                        renderer.setColor("red");
                        for (var i = this.body.shapes.length, shape; i--, (shape = this.body.shapes[i]);) {
                            renderer.drawShape(shape);
                            _this.counters.inc("shapes");
                        }
                        renderer.restore();
                    }

                    if (me.debug.renderVelocity && (this.body.vel.x || this.body.vel.y)) {
                        bounds.copy(this.getBounds());
                        bounds.pos.sub(this.ancestor._absPos);
                        // draw entity current velocity
                        var x = bounds.width / 2;
                        var y = bounds.height / 2;

                        renderer.save();
                        renderer.setLineWidth(1);

                        renderer.setColor("blue");
                        renderer.translate(-x, -y);
                        renderer.strokeLine(0, 0, ~~(this.body.vel.x * (bounds.width / 2)), ~~(this.body.vel.y * (bounds.height / 2)));
                        _this.counters.inc("velocity");

                        renderer.restore();
                    }
                }
                // call the original me.Entity.postDraw function
                this._patched(renderer);
            });

            // patch container.js
            me.plugin.patch(me.Container, "draw", function (renderer, rect) {
                // call the original me.Container.draw function
                this._patched(renderer, rect);

                // check if debug mode is enabled
                if (!_this.visible) {
                    // don't do anything else if the panel is hidden
                    return;
                }

                // increment counters
                _this.counters.inc("bounds");
                _this.counters.inc("children");

                if (me.debug.renderHitBox) {
                    renderer.save();
                    renderer.setLineWidth(1);

                    // draw the bounding rect shape
                    renderer.setColor("orange");
                    bounds.copy(this.getBounds());
                    if (this.ancestor) {
                        bounds.pos.sub(this.ancestor._absPos);
                    }
                    renderer.drawShape(bounds);

                    // draw the children bounding rect shape
                    renderer.setColor("purple");
                    bounds.copy(this.childBounds);
                    if (this.ancestor) {
                        bounds.pos.sub(this.ancestor._absPos);
                    }
                    renderer.drawShape(bounds);

                    renderer.restore();
                }
            });
        },

        /**
         * show the debug panel
         */
        show : function () {
            if (!this.visible) {
                // add the debug panel to the game world
                me.game.world.addChild(this, Infinity);
                // register a mouse event for the checkboxes
                me.input.registerPointerEvent("pointerdown", this, this.onClick.bind(this));
                // mark it as visible
                this.visible = true;
                // force repaint
                me.game.repaint();
            }
        },

        /**
         * hide the debug panel
         */
        hide : function () {
            if (this.visible) {
                // release the mouse event for the checkboxes
                me.input.releasePointerEvent("pointerdown", this);
                // remove the debug panel from the game world
                me.game.world.removeChild(this, true);
                // mark it as invisible
                this.visible = false;
                // force repaint
                me.game.repaint();
            }
        },


        /** @private */
        update : function () {
            return this.visible;
        },

        /** @private */
        onClick : function (e)  {
            // check the clickable areas
            if (this.area.renderHitBox.containsPoint(e.gameX, e.gameY)) {
                me.debug.renderHitBox = !me.debug.renderHitBox;
            } else if (this.area.renderVelocity.containsPoint(e.gameX, e.gameY)) {
                // does nothing for now, since velocity is
                // rendered together with hitboxes (is a global debug flag required?)
                me.debug.renderVelocity = !me.debug.renderVelocity;
            } else if (this.area.renderQuadTree.containsPoint(e.gameX, e.gameY)) {
                me.debug.renderQuadTree = !me.debug.renderQuadTree;
            }
            // force repaint
            me.game.repaint();
        },

        /** @private */
        drawQuadTreeNode : function (renderer, node) {
            var bounds = node.bounds;

            // draw the current bounds
            if (node.nodes.length === 0) {
                // cap the alpha value to 0.4 maximum
                var _alpha = (node.objects.length * 0.4) / me.collision.maxChildren;
                if (_alpha > 0.0) {
                    renderer.save();
                    renderer.setColor("rgba(255,0,0," + _alpha + ")");
                    renderer.fillRect(bounds.pos.x, bounds.pos.y, bounds.width, bounds.height);
                    renderer.restore();
                }
            } else {
                //has subnodes? drawQuadtree them!
                for (var i = 0; i < node.nodes.length; i++) {
                    this.drawQuadTreeNode(renderer, node.nodes[i]);
                }
            }
        },

        /** @private */
        drawQuadTree : function (renderer) {
            // save the current globalAlpha value
            var _alpha = renderer.globalAlpha();
            var x = me.game.viewport.pos.x;
            var y = me.game.viewport.pos.y;

            renderer.translate(-x, -y);

            this.drawQuadTreeNode(renderer, me.collision.quadTree);

            renderer.translate(x, y);

            renderer.setGlobalAlpha(_alpha);
        },

        /** @private */
        drawMemoryGraph : function (renderer, endX) {
            if (window.performance && window.performance.memory) {
                var usedHeap  = Number.prototype.round(window.performance.memory.usedJSHeapSize / 1048576, 2);
                var totalHeap =  Number.prototype.round(window.performance.memory.totalJSHeapSize / 1048576, 2);
                var maxLen = ~~(endX - this.memoryPositionX - 5);
                var len = maxLen * (usedHeap / totalHeap);

                renderer.setColor("#0065AD");
                renderer.fillRect(this.memoryPositionX, 0, maxLen, 20);
                renderer.setColor("#3AA4F0");
                renderer.fillRect(this.memoryPositionX + 1, 1, len - 1, 17);

                this.font.draw(renderer, "Heap : " + usedHeap + "/" + totalHeap + " MB", this.memoryPositionX + 5, 2 * this.mod);
            } else {
                // Heap Memory information not available
                this.font.draw(renderer, "Heap : ??/?? MB", this.memoryPositionX, 2 * this.mod);
            }
        },

        /** @private */
        draw : function (renderer) {
            renderer.save();

            // draw the QuadTree (before the panel)
            if (me.debug.renderQuadTree === true) {
                this.drawQuadTree(renderer);
            }

            // draw the panel
            renderer.setGlobalAlpha(0.5);
            renderer.setColor("black");
            renderer.fillRect(
                this.left,  this.top,
                this.width, this.height
            );
            renderer.setGlobalAlpha(1.0);
            renderer.setColor("white");

            this.font.textAlign = "left";

            this.font.draw(renderer, "#objects : " + me.game.world.children.length, 5 * this.mod, 2 * this.mod);
            this.font.draw(renderer, "#draws   : " + me.game.world.drawCount, 5 * this.mod, 10 * this.mod);

            // debug checkboxes
            this.font.draw(renderer, "?hitbox   [" + (me.debug.renderHitBox ? "x" : " ") + "]",   75 * this.mod, 2 * this.mod);
            this.font.draw(renderer, "?velocity [" + (me.debug.renderVelocity ? "x" : " ") + "]", 75 * this.mod, 10 * this.mod);

            this.font.draw(renderer, "?QuadTree [" + (me.debug.renderQuadTree ? "x" : " ") + "]", 150 * this.mod, 2 * this.mod);

            // draw the update duration
            this.font.draw(renderer, "Update : " + this.frameUpdateTime.toFixed(2) + " ms", 225 * this.mod, 2 * this.mod);
            // draw the draw duration
            this.font.draw(renderer, "Draw   : " + this.frameDrawTime.toFixed(2) + " ms", 225 * this.mod, 10 * this.mod);


            // Draw color code hints (not supported with bitmapfont)
            //this.font.fillStyle.copy("red");
            this.font.draw(renderer, "Shapes   : " + this.counters.get("shapes"), 5 * this.mod, 17 * this.mod);

            //this.font.fillStyle.copy("green");
            this.font.draw(renderer, "Sprites   : " + this.counters.get("sprites"), 75 * this.mod, 17 * this.mod);

            //this.font.fillStyle.copy("blue");
            this.font.draw(renderer, "Velocity  : " + this.counters.get("velocity"), 150 * this.mod, 17 * this.mod);

            //this.font.fillStyle.copy("orange");
            this.font.draw(renderer, "Bounds : " + this.counters.get("bounds"), 225 * this.mod, 17 * this.mod);

            //this.font.fillStyle.copy("purple");
            this.font.draw(renderer, "Children : " + this.counters.get("children"), 325 * this.mod, 17 * this.mod);

            // Reset font style
            //this.font.setFont("courier", this.font_size, "white");

            // draw the memory heap usage
            var endX = this.width - 5;
            this.drawMemoryGraph(renderer, endX - this.help_str_len);

            this.font.textAlign = "right";

            // some help string
            this.font.draw(renderer, this.help_str, endX, 17 * this.mod);

            //fps counter
            var fps_str = me.timer.fps + "/" + me.sys.fps + " fps";
            this.font.draw(renderer, fps_str, endX, 2 * this.mod);

            renderer.restore();
        },

        /** @private */
        onDestroyEvent : function () {
            // hide the panel
            this.hide();
            // unbind keys event
            me.input.unbindKey(this.toggleKey);
            me.event.unsubscribe(this.keyHandler);
        }
    });

    /**
     * @class
     * @public
     * @extends me.plugin.Base
     * @memberOf me
     * @constructor
     */
    me.debug.Panel = me.plugin.Base.extend(
    /** @scope me.debug.Panel.prototype */
    {
        /** @private */
        init : function (debugToggle) {
            // call the super constructor
            this._super(me.plugin.Base, "init");
            this.panel = new DebugPanel(debugToggle);

            // if "#debug" is present in the URL
            if (me.game.HASH.debug === true) {
                this.show();
            } // else keep it hidden
        },

        /** @private */
        show : function () {
            this.panel.show();
        },

        /** @private */
        hide : function () {
            this.panel.hide();
        },

        /** @private */
        toggle : function () {
            if (this.panel.visible) {
                this.panel.hide();
            } else {
                this.panel.show();
            }
        }


    });

    // automatically register the debug panel
    me.device.onReady(function () {
        me.plugin.register.defer(this, me.debug.Panel, "debugPanel",
            me.game.HASH.debugToggleKey ? me.game.HASH.debugToggleKey.charCodeAt(0) - 32 : undefined
        );
    });

    /*---------------------------------------------------------*/
    // END END END
    /*---------------------------------------------------------*/
})();