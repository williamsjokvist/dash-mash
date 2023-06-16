/*
 * 
 *      An enemy entity
 * 
 */

game.EnemyEntity = me.Entity.extend({

    init: function (x, y, settings) {

        settings.image = "test_sprite";
        var width = settings.width;
        settings.framewidth = settings.width = 64;
        settings.frameheight = settings.height = 64;
        settings.shapes[0] = new me.Rect(0, 0, settings.framewidth, settings.frameheight);

        this._super(me.Entity, 'init', [x, y, settings]);

        x = this.pos.x;
        this.startX = x;
        this.endX = x + width - settings.framewidth;
        this.pos.x = x + width - settings.framewidth;
        this.walkLeft = false;
        this.alive = true;

        this.body.setVelocity(1, 10);
        this.body.setMaxVelocity(8, 20);
        this.body.setFriction(.1, 0);
        this.body.collisionType = me.collision.types.ENEMY_OBJECT;

        this.renderable.addAnimation("walk", [2, 3, 4, 5], 100);
        this.renderable.addAnimation("stand", [{name: 1, delay: 100}, {name: 0, delay: 200}]);
        this.renderable.addAnimation("jump", [6], 0);
        this.renderable.addAnimation("fall", [6], 0);
        this.renderable.addAnimation("crouch", [9], 0);
        this.renderable.addAnimation("die", [{name: 3, delay: 200}, {name: 4, delay: 100}, {name: 5, delay: Infinity}]);
        this.renderable.addAnimation("slide", [8], 0);
        this.renderable.setCurrentAnimation("stand");
        this.renderable.animationspeed = 70;

    },

    /**
     * update the enemy pos
     */

    update: function (dt) {

        if (this.alive) {
            if (this.walkLeft && this.pos.x <= this.startX) {
                this.walkLeft = false;
                this.renderable.flipX(false);
                if (!this.renderable.isCurrentAnimation("walk")) {
                    this.renderable.setCurrentAnimation("walk");
                }
            } else if (!this.walkLeft && this.pos.x >= this.endX) {
                this.walkLeft = true;
                this.renderable.flipX(true);
                if (!this.renderable.isCurrentAnimation("walk")) {
                    this.renderable.setCurrentAnimation("walk");
                }
            }

            // make it walk
            this.renderable.flipX(this.walkLeft);
            this.body.vel.x += (this.walkLeft) ? -this.body.accel.x * me.timer.tick : this.body.accel.x * me.timer.tick;
        } else {
            this.body.vel.x = 0;
        }

        this.body.update(dt);

        me.collision.check(this);
        return (this._super(me.Entity, 'update', [dt]) || this.body.vel.x !== 0 || this.body.vel.y !== 0);
    },

    onCollision: function (response, other) {

        this.body.setCollisionMask(me.collision.types.WORLD_SHAPE | me.collision.types.PLAYER_OBJECT);

        switch (response.b.body.collisionType) {
            case me.collision.types.PLAYER_OBJECT:
                if (this.alive && response.b.body.attacking === true) {
                  
                    game.data.score += 5;

                    this.body.setCollisionMask(me.collision.types.NO_OBJECT);

                    let self = this;
                    console.log("enemy dead");
                    this.renderable.flicker(750, function () {
                        me.game.world.removeChild(self);
                    });

                }

            case me.collision.types.ENEMY_OBJECT:
            {
                return true;
            }

            case me.collision.types.WORLD_SHAPE:
                {
                    return true;
                }
                break;
        }
    }
});