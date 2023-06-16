(function () {
    // a basic progress bar object
    var ProgressBar = me.Renderable.extend({
        /**
         * @ignore
         */

        init: function (x, y, w, h) {
            this._super(me.Renderable, "init", [x, y, w, h]);

            this.el = document.createElement('progress');
            this.el.setAttribute("max", "1280");

            document.body.appendChild(this.el);

            // flag to know if we need to refresh the display
            this.invalidate = false;
            // current progress
            this.progress = 0;

            this.anchorPoint.set(0, 0);

        },
        /**
         * make sure the screen is refreshed every frame
         * @ignore
         */
        onProgressUpdate: function (progress) {
            this.progress = ~~(progress * this.width);
            this.invalidate = true;
            this.el.setAttribute("value", this.progress);
        },
        /**
         * @ignore
         */
        update: function () {
            if (this.invalidate === true) {
                // clear the flag
                this.invalidate = false;
                // and return true
                return true;
            }
            // else return false
            return false;
        },
        /**
         * draw function
         * @ignore
         */
        draw: function (renderer) {

            this.el.animate([
                {'background-position': '0 0'},
                {'background-position': '40px 0'}
            ], {
                duration: 3000,
                iterations: Infinity
            });
            // document.body.appendChild(this.el);

            // background 
            let bg = new me.Sprite(0, 0, {image: me.loader.getImage('loading_screen')});
            bg.anchorPoint.set(0, 0);
            bg.scale(me.game.viewport.width / bg.width, me.game.viewport.height / bg.height);
            me.game.world.addChild(bg, 1);
        }
    });

    me.DefaultLoadingScreen = me.ScreenObject.extend({
        /**
         * call when the loader is resetted
         * @ignore
         */
        onResetEvent: function () {


            // progress bar
            var progressBar = new ProgressBar(
                    0,
                    me.video.renderer.getHeight() / 2,
                    me.video.renderer.getWidth(),
                    15 // bar height
                    );

            this.loaderHdlr = me.event.subscribe(
                    me.event.LOADER_PROGRESS,
                    progressBar.onProgressUpdate.bind(progressBar)
                    );

            this.resizeHdlr = me.event.subscribe(
                    me.event.VIEWPORT_ONRESIZE,
                    progressBar.resize.bind(progressBar)
                    );

            me.game.world.addChild(progressBar, 2);

        },
        /**
         * destroy object at end of loading
         * @ignore
         */
        onDestroyEvent: function () {
            // cancel the callback
            me.event.unsubscribe(this.loaderHdlr);
            me.event.unsubscribe(this.resizeHdlr);
            this.loaderHdlr = this.resizeHdlr = null;
            let elem = document.getElementsByTagName("progress")[0];
            if (elem) {
                document.body.removeChild(elem);
            }
        }
    });
})();