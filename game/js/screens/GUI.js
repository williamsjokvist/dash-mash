game.GUI = me.ScreenObject.extend({
    init: function () {
        this._super(me.ScreenObject, 'init', arguments);
    }
});

game.GUI.Avatar = me.Sprite.extend({
    init: function () {
        this._super(me.Sprite, "init", [150, 150, {image: `avatar`, framewidth: 200, frameheight: 200, anchorPoint: new me.Vector2d(0.5, 0.5)}]);
        this.animationpause = true;
    },
    resize: function (w, h) {
        this.scale(w, h);
    }
});

game.GUI.Check = me.Sprite.extend({
    init: function (x,y) {
        this._super(me.Sprite, "init", [x, y, {image: `check`, spritewidth: 55, width: 55}]);
        this.alwaysUpdate = true;
        this.setAnimationFrame();

        this.addAnimation("check", [0,1], 5);
        this.addAnimation("uncheck", [0], 0);
        
        this.setCurrentAnimation("uncheck");
    },
    update: function () {
        return true;
    }
});

game.GUI.Heading = me.Sprite.extend({
    init: function (i) {
        this._super(me.Sprite, "init", [me.game.viewport.width / 2, 100, {image: `heading_${i}`}]);
        this.pos.z = 4;
    }
});

game.GUI.LowText = me.Renderable.extend({
    init: function () {
        this._super(me.Renderable, 'init', [0, 0, me.game.viewport.width, me.game.viewport.height]);
        this.bfont = new me.Font("Visitor", 32, "#F90");
    },

    draw: function (renderer, content) {
        this.bfont.draw(renderer, content, 50, me.game.viewport.height - 50);
    }
});

game.GUI.ParticleStream = me.ParticleEmitter.extend({
    init: function () {
        this._super(me.ParticleEmitter, "init", [me.game.viewport.getWidth() / 12, y = me.game.viewport.getHeight() / 2, {
                image: me.loader.getImage('smoke'),
                width: me.game.viewport.getWidth(),
                height: me.game.viewport.getHeight(),
                totalParticles: 300,
                angle: 2.06683727209855,
                minLife: 400,
                minRotation: -0.248020472651826,
                maxRotation: 0.137789151473236,
                minStartScale: 0,
                maxStartScale: 0.328947368421053,
                maxParticles: 21,
                frequency: 50
            }]);

        this.pos.z = 1;
    }
});

function addGeneric() {
    /*Adds generic GUI children to world*/

    /*Particle Stream*/
    let pStream = new game.GUI.ParticleStream();
    me.game.world.addChild(pStream);
    pStream.streamParticles();

    /*Username*/
    me.game.world.addChild(new (me.Renderable.extend({
        init: function () {
            this._super(me.Renderable, 'init', [0, 0, me.game.viewport.width, me.game.viewport.height]);
            this.font = new me.Font("Visitor", 24, "#F90");
        },

        draw: function (renderer) {
            this.font.setFont("Visitor", 24, "#F90");
            this.font.draw(renderer, rq.username, me.game.viewport.width - 500, 400);
            this.font.setFont("Visitor", 24, "#FFF");
            this.font.draw(renderer, "Score: " + rq.score, me.game.viewport.width - 500, 420);
        }
    })));

    /*User avatar*/
    let avatar = new game.GUI.Avatar();
    avatar.resize(.4, .4);
    me.game.world.addChild(avatar);

    /*Background*/
    this.bg = new me.Sprite(0, 0, {image: me.loader.getImage('wall')});
    this.bg.anchorPoint.set(0, 0);
    this.bg.scale(me.game.viewport.width / bg.width, me.game.viewport.height / bg.height);
    me.game.world.addChild(this.bg, 1);
}

function addLow(content) {
    /*Adds text to bottom*/
    let lowText = new game.GUI.LowText(content);
    me.game.world.addChild(lowText);
}

function addSub() {
    /*Submenu children*/

    /*Go Back sprite*/
    let goBack = new me.Sprite(50, me.game.viewport.height - 100, {image: me.loader.getImage('goBack'), anchorPoint: new me.Vector2d(0.0, 0.0)});
    me.game.world.addChild(goBack);
}