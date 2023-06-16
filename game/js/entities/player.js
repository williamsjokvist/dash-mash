/**
 * Player Entity
 */
game.PlayerEntity = me.Entity.extend({

    init: function (x, y, settings) {

        this._super(me.Entity, 'init', [x, y, settings]);
        /* Entity settings */
        settings.spritewidth = 72;
        settings.height = 72;
        settings.width = 32;
        me.state.current().player = this;
        /* Body physics */
        this.body.setVelocity(1, 10);
        this.body.setMaxVelocity(8, 20);
        this.body.setFriction(.1, 0);
        this.body.collisionType = me.collision.types.PLAYER_OBJECT;
        me.game.viewport.follow(this.pos, me.game.viewport.AXIS.BOTH);
        this.alwaysUpdate = true;
        this.body.walljumping = false;
        this.body.attack = false;
        this.body.standing = true;
        this.direction = 1;
        /* Animations */
        this.renderable.addAnimation("walk", [2, 3, 4, 5], 100);
        this.renderable.addAnimation("stand", [{name: 1, delay: 100}, {name: 0, delay: 200}]);
        this.renderable.addAnimation("jump", [6], 0);
        this.renderable.addAnimation("fall", [6], 0); //9
        this.renderable.addAnimation("crouch", [9], 0);
        this.renderable.addAnimation("die", [{name: 3, delay: 200}, {name: 4, delay: 100}, {name: 5, delay: Infinity}]);
        this.renderable.addAnimation("slide", [8], 0);
        this.renderable.setCurrentAnimation("stand");
    
         this.anchorPoint.set(-0.5, 0.0);
    
    },
    start: function () {
        this.Transform = transform;
    },
    jump: function () {
        this.body.jumping = true;
        this.body.vel.y = -this.body.maxVel.y * me.timer.tick;
        me.audio.play("jump", false, null, 1);
    },
    attack: function () {
        this.body.attack = true;
        this.body.shapes = [];
        this.body.addShape(new me.Rect(0, 48, 32, 72));
        this.body.updateBounds();
        let self = this;
        setTimeout(function () {
            self.body.attack = false;
        }, 20);
        //let sword = me.pool.pull("Sword", this.pos.x - 5, this.pos.y - 5);
        //me.game.world.addChild(sword, 5);
    },
    hurt: function () {
        this.renderable.flicker(450, function () {
            game.data.score -= 5;
            me.game.viewport.shake(10, 500, me.game.viewport.AXIS.BOTH);
            this.body.setCollisionMask(me.collision.types.WORLD_SHAPE);
        }.bind(this));
    },
    slide: function () {

        this.body.sliding = true;
        let self = this;

        this.body.shapes = [];
        this.body.addShape(new me.Rect(0, 48, this.width, 24));
        this.body.updateBounds();
        this.anchorPoint.set(-0.5, -2.0);

        /*
         setTimeout(function () {
         self.body.sliding = false;
         
         self.body.shapes = [];
         self.body.addShape(new me.Rect(0, 0, 72, 32));
         self.body.updateBounds();
         self.anchorPoint.set(-0.5, 0.0);

         }, 100);
         
         /* Resizing fungerar ej, syntax fel?. Möjl. bättre lösn.
         * 
         let self = this;
         let shape = self.body.getShape(0);
         shape.scale(1, 0.5);
         setTimeout(function () {
         shape.scale(1, 1);
         }, 20); */
        if (me.input.isKeyPressed('right')) {
            this.body.vel.x -= this.body.accel.x * me.timer.tick;
        } else if (me.input.isKeyPressed('left')) {
            this.body.vel.x += this.body.accel.x * me.timer.tick;
        }

        if (!this.renderable.isCurrentAnimation("slide")) {
            this.renderable.setCurrentAnimation("slide");
        }

    },
    crouch: function () {
        this.body.crouching = true;
        this.body.vel.x = 0;
        if (!this.renderable.isCurrentAnimation("crouch")) {
            this.renderable.setCurrentAnimation("crouch");
        }
    },
    update: function (dt) {

        /* Walking */
        if (me.input.isKeyPressed('right')) {
            this.renderable.flipX(false);
            this.body.vel.x += this.body.accel.x * me.timer.tick;
            if (!this.renderable.isCurrentAnimation("walk")) {
                this.renderable.setCurrentAnimation("walk");
            }
        } else if (me.input.isKeyPressed('left')) {
            this.renderable.flipX(true);
            this.body.vel.x -= this.body.accel.x * me.timer.tick;
            if (!this.renderable.isCurrentAnimation("walk")) {
                this.renderable.setCurrentAnimation("walk");
            }
        } else {
            this.body.standing = true;
            this.body.vel.x = 0;
            if (!this.renderable.isCurrentAnimation("stand")) {
                this.renderable.setCurrentAnimation("stand");
            }
        }

        /* Jumping */
        if (me.input.isKeyPressed('jump') && ((!this.body.jumping && !this.body.falling && !this.body.sliding) || ((this.body.walljumping && this.body.jumping || this.body.walljumping && this.body.falling)))) {
            this.jump();
        }

        /* Crouching */
        if (me.input.isKeyPressed("down") && !this.body.jumping && !this.body.falling && !this.body.sliding) {
            this.crouch();
        } else {
            this.body.crouching = false;
        }

        /* Sliding */
        if (me.input.isKeyPressed("slide") && !this.body.falling && !this.body.jumping && !this.body.crouching) {
            this.slide();
        } else {
            this.body.sliding = false;
            this.body.shapes = [];
            this.body.addShape(new me.Rect(0, 0, this.width, this.height));
            this.body.updateBounds();
            this.anchorPoint.set(-0.5, 0.0);
        }

        /* Attacking */
        if (me.input.isKeyPressed("sword") && this.body.attack === false) {
            this.attack();
            //setTimeout(this.attack, 20); delayed attack
        }

        /* Animation setters */
        if (this.body.jumping && !this.renderable.isCurrentAnimation("jump")) {
            this.renderable.setCurrentAnimation("jump");
        } else

        if (this.body.falling && !this.renderable.isCurrentAnimation("fall")) {
            this.renderable.setCurrentAnimation("fall");
        }

        /* Reload level if body falls into hole */
        if (!this.inViewport && (this.pos.y > me.video.renderer.getHeight())) {
            me.game.world.removeChild(this);
            me.game.viewport.fadeIn("#000000", 200, function () {
                me.audio.play("UI_START", false, null, 1);
                me.game.viewport.shake(10, 500, me.game.viewport.AXIS.BOTH);
                me.levelDirector.reloadLevel();
                me.game.viewport.fadeOut("#000000", 200);
                game.data.score -= 50;
            });
            return true;
        }

        this.body.update(dt);
        me.collision.check(this);
        return (this._super(me.Entity, 'update', [dt]) || this.body.vel.x !== 0 || this.body.vel.y !== 0);
    },
    onCollision: function (response, other) {
        this.body.setCollisionMask(me.collision.types.WORLD_SHAPE | me.collision.types.ENEMY_OBJECT);
        switch (response.b.body.collisionType) {
            case me.collision.types.WORLD_SHAPE:

                if (other.type === "platform") {
                    if (this.body.falling && !me.input.isKeyPressed('down') && (response.overlapV.y > 0) && (~~this.body.vel.y >= ~~response.overlapV.y)) {
                        response.overlapV.x = 0;
                        return true;
                    }
                    return false;
                }
                break;
            case me.collision.types.ENEMY_OBJECT:

                if (this.body.attack === false) {
                    /*Post hit invincibility*/
                    this.hurt();
                    return false;
                }

            default:
                return false;
        }

        switch (other.body.collisionType) {
            case me.collision.types.WORLD_SHAPE:

                if (other.type === "goal") {
                    this.renderable.flicker(10, function () {
                        this.body.setCollisionMask(me.collision.types.WORLD_SHAPE);
                        me.game.world.removeChild(this);
                        me.state.change(me.state.SCORE);
                        me.audio.fade("radioactive", 1.0, 0.0, 100);
                    }.bind(this));
                    return true;
                }

                if (other.type === "slope") {
                    response.overlapV.y = Math.abs(response.overlap);
                    response.overlapV.x = 0;
                    return true;
                }

                if (other.type === "walljump" && !this.body.walljumping) {

                    this.body.walljumping = true;
                    if (me.input.isKeyPressed('right') && !me.input.isKeyPressed('left')) {
                        this.body.vel.x += 12;
                    } else if (me.input.isKeyPressed('left') && !me.input.isKeyPressed('right')) {
                        this.body.vel.x -= 12;
                    }
                } else {
                    this.body.walljumping = false;
                }

                break;
        }

        return true;
    }
});