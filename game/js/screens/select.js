game.LevelScreen = me.ScreenObject.extend({

    init: function () {
        this._super(me.ScreenObject, 'init', arguments);
    },

    onResetEvent: function () {

        this.handler = me.event.subscribe(me.event.KEYDOWN, this.keyHandler.bind(this));
        this.finished = false;

        /*Levels*/
        this.levels = [0, 1, 2];

        for (let i = 0; i < this.levels.length; i++) {
            me.game.world.addChild(new game.LevelScreen.Level((i * 340 + 300), 400, i));
        }

        /*Level Selector*/
        this.selector = new game.LevelScreen.Selector();
        this.selectorIndex = 0;
        this.selector.setLevel(this.selectorIndex);
        me.game.world.addChild(this.selector);

        addGeneric();
        addSub();

        /*Text*/
        me.game.world.addChild(new (me.Renderable.extend({
            init: function () {
                this._super(me.Renderable, 'init', [0, 0, me.game.viewport.width, me.game.viewport.height]);

                this.font = new me.Font('Unica One', 64, '#F90', 'center');
                this.alphaTween = new me.Tween(this.font).to({alpha: 0}, 1000);
                this.chainedTween = new me.Tween(this.font).to({alpha: 1}, 1000);
                this.alphaTween.chain(this.chainedTween);
                this.chainedTween.chain(this.alphaTween);
                this.alphaTween.start();

                this.chainedTween.easing(me.Tween.Easing.Back.Out);
                this.alphaTween.easing(me.Tween.Easing.Cubic.In);
            },

            draw: function (renderer) {
                this.font.setFont("Unica One", 64, "#F90");
                this.font.draw(renderer, '<', 700, 700);
                this.font.draw(renderer, '>', 1850, 700);
            }
        })));

        me.game.world.addChild(new game.GUI.Heading(0));
    },

    keyHandler: function (action, keyCode, edge) {
        if (action === "enter" || action === "jump" && !this.finished) {
            game.data.level = this.selectorIndex;
            me.state.change(me.state.PLAY);
            me.audio.play("UI_START", false, null, 1);

            me.audio.fade("sphere", .5, 0.0, 1000);
            me.audio.pause("sphere");

            this.finished = true;

        } else if (action === "left") {
            this.moveSelector(-1);
        } else if (action === "right") {
            this.moveSelector(1);
        } else if (action === "back" || action === "slide") {
            me.state.change(me.state.MENU);
        }
    },

    moveSelector: function (amount) {
        this.selectorIndex = (this.selectorIndex + amount) % this.levels.length;
        if (this.selectorIndex < 0) {
            this.selectorIndex = this.levels.length - 1;
        }
        this.selector.setLevel(this.levels[this.selectorIndex]);
    },

    onDestroyEvent: function () {
        me.event.unsubscribe(this.handler);
        me.game.world.removeChild(this.selector);
    }
});

game.LevelScreen.Selector = me.Sprite.extend({
    init: function () {
        this._super(me.Sprite, "init", [0, 0, {image: `lvlselector`}]);
        this.bounceTween = new me.Tween(this).to({alpha: .6}, 1000);
        this.chainedBounce = new me.Tween(this).to({alpha: 1}, 1000);
        this.bounceTween.chain(this.chainedBounce);
        this.chainedBounce.chain(this.bounceTween);
        this.bounceTween.start();
    },
    setLevel: function (l) {
        this.pos.x = l * 340 + 300;
        this.pos.y = 400;
        this.pos.z = 5;
        this.level = l;
    },
    update: function () {
        return true;
    }
});

game.LevelScreen.Level = me.Sprite.extend({
    init: function (x, y, level)
    {
        this._super(me.Sprite, "init", [x, y, {image: `lvlselect_${level}`}]);

        this.pos.z = 4;
        this.level = `${level}`;
    }
});