/**
 * Sword Entity
 */
game.SwordEntity = me.Entity.extend({
    init: function (x, y, settings) {
        // entity settings
        settings.image = "sword";
        settings.width = 104;
        settings.height = 41;
        // settings.shapes[0] = new me.Rect(0, 0, settings.width, settings.height);

        this.body.addShape(new me.Rect(0, 0, settings.width, settings.height));

        // call the constructor
        this._super(me.Entity, 'init', [x, y, settings]);

        // body physics
        this.body.collisiontype = me.collision.types.MELEE_ATK;

        this.updatePos();
    },

    updatePos: function () {
        //    this.pos.x = game.player.pos.x - 5;
        //    this.pos.y = game.player.pos.y;
    },

    update: function (dt) {
        this.updatePos();
    },

    onCollision: function (response, other) {
        return false;
    }
});

//game.player = me.game.world.getChildByProp("name", "Player");