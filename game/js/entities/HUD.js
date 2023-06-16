
game.HUD = game.HUD || {};
game.HUD.Container = me.Container.extend({

    init: function () {
        // call the constructor
        this._super(me.Container, 'init');
        // persistent across level change
        this.isPersistent = true;
        // make sure we use screen coordinates
        this.floating = true;
        // give a name
        this.name = "HUD";
        this.addChild(new game.HUD.ScoreItem(5, 5));
        this.addChild(new game.HUD.TimeItem(0, 5));
    }
});

game.HUD.ScoreItem = me.Renderable.extend({
    /**
     * constructor
     */
    init: function (x, y) {
        this.score = -1;
        this._super(me.Renderable, 'init', [0, 0, 0, 0]);
        this.font = new me.BitmapFont(me.loader.getBinary('MapixHUD'), me.loader.getImage('MapixHUD'));
    },
    update: function () {
        // returns true if score has been updated
        if (this.score !== game.data.score) {
            this.score = game.data.score;
            return true;
        }
        return false;
    },
    draw: function (renderer) {
        this.font.draw(renderer, "Score: " + this.score, 20, 650);
    }
});
/**
 * Time HUD Item
 */
game.HUD.TimeItem = me.Renderable.extend({
    init: function (x, y) {
        this.time = 0;
        this._super(me.Renderable, 'init', [0, 0, 0, 0]);
        this.font = new me.Font("Visitor", 32, "#F90");
    },
    update: function () {
        return true;
    },
    draw: function (renderer) {
        var divisor_for_minutes = this.remainingTime % (60 * 60);
        var minutes = Math.floor(divisor_for_minutes / 60);
        var divisor_for_seconds = divisor_for_minutes % 60;
        var seconds = Math.ceil(divisor_for_seconds);
        this.font.draw(renderer, "Time: " + minutes + ":" + ('00' + seconds).slice(-2), me.game.viewport.width + this.pos.x, me.game.viewport.height + this.pos.y);
    }
});