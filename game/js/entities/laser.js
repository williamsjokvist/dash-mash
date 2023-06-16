game.Laser = me.Entity.extend({
    init : function (x, y) {
        game.Laser.width = 5;
        game.Laser.heights = 28;
        this._super(me.Entity, "init", [x, y, { width: game.Laser.width, height: game.Laser.height }]);
        this.z = 5;
        this.body.setVelocity(0, 300);
        this.body.collisionType = me.collision.types.PROJECTILE_OBJECT;
        this.renderable = new (me.Renderable.extend({
            init : function () {
                this._super(me.Renderable, "init", [0, 0, game.Laser.width, game.Laser.height]);
            },
            destroy : function () {},
            draw : function (renderer) {
                var color = renderer.getColor();
                renderer.setColor('#5EFF7E');
                renderer.fillRect(0, 0, this.width, this.height);
                renderer.setColor(color);
            }
        }));
        this.alwaysUpdate = true;
    },

    update : function (time) {
        this.body.vel.y -= this.body.accel.y * time / 1000;
        if (this.pos.y + this.height <= 0) {
            me.game.world.removeChild(this);
        }
        
        this.body.update();
        me.collision.check(this);

        return true;
    }
});

