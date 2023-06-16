game.PlayScreen = me.ScreenObject.extend({
    init: function () {
        this._super(me.ScreenObject, 'init', arguments);
    },

    onResetEvent: function () {
        
        me.levelDirector.loadLevel("LVL_" + game.data.level);
        game.data.score = 0;

        this.HUD = new game.HUD.Container();
        me.game.world.addChild(this.HUD);
        
        me.audio.playTrack("radioactive", 0.3);
    },

    onDestroyEvent: function () {
        me.game.world.removeChild(this.HUD);
        me.audio.stopTrack();
    }
});
