game.TitleScreen = me.ScreenObject.extend({

    init: function () {
        this._super(me.ScreenObject, 'init', arguments);
    },

    onResetEvent: function () {

        /*If greeting, remove*/

        let greet = document.getElementsByClassName("greet")[0];
        if (greet.hasAttribute("open")) {
            greet.removeAttribute("open");
        }

        this.handler = me.event.subscribe(me.event.KEYDOWN, this.keyHandler.bind(this));
        this.finished = false;

        me.input.bindKey(me.input.KEY.ENTER, "enter", true);
        me.input.bindGamepad(0, {type: "buttons", code: me.input.GAMEPAD.BUTTONS.START, threshold: 0.5}, me.input.KEY.ENTER); //Start button (Axis)

        this.menus = [0, 1, 2];
        for (let i = 0; i < this.menus.length; i++) {
            me.game.world.addChild(new game.TitleScreen.Item(me.game.viewport.width / 2, (i * 120 + 400), i));
        }

        this.selector = new game.TitleScreen.Selector();
        this.selectorIndex = 0;
        this.selector.setItem(this.selectorIndex);
        me.game.world.addChild(this.selector);

        addGeneric();

        let logo = new me.Sprite(me.game.viewport.width / 2, 200, {image: "logo", framewidth: 916, frameheight: 139, anchorPoint: new me.Vector2d(0.5, 0.5)});
        me.game.world.addChild(logo);
    },

    keyHandler: function (action, keyCode, edge) {
        if (action === "up") {
            this.moveSelector(-1);
        } else if (action === "down") {
            this.moveSelector(1);
        } else if (action === "enter" || action === "jump" && !this.finished) {

            if (this.selectorIndex === 0) {
                me.state.change(me.state.READY);
            } else if (this.selectorIndex === 1) {
                me.state.change(me.state.LDRBOARD);
            } else if (this.selectorIndex === 2) {
                me.state.change(me.state.SETTINGS);
            }

            me.audio.play("UI_START", false, null, 1);
            this.finished = true;
        }
    },

    moveSelector: function (amount) {
        this.selectorIndex = (this.selectorIndex + amount) % this.menus.length;
        if (this.selectorIndex < 0) {
            this.selectorIndex = this.menus.length - 1;
        }
        this.selector.setItem(this.menus[this.selectorIndex]);
    },

    onDestroyEvent: function () {
        me.event.unsubscribe(this.handler);
        me.game.world.removeChild(this.selector);
    }
});

game.TitleScreen.Selector = me.Sprite.extend({
    init: function () {
        this._super(me.Sprite, "init", [0, 0, {image: `menu_selector`}]);
        this.bounceTween = new me.Tween(this).to({alpha: .4}, 1000);
        this.chainedBounce = new me.Tween(this).to({alpha: 1}, 1000);
        this.bounceTween.chain(this.chainedBounce);
        this.chainedBounce.chain(this.bounceTween);
        this.bounceTween.start();
    },
    setItem: function (i) {
        this.pos.x = me.game.viewport.width / 2;
        this.pos.y = (i * 120 + 400);
        this.pos.z = 5;
    },
    update: function () {
        return true;
    }
});

game.TitleScreen.Item = me.Sprite.extend({
    init: function (x, y, i)
    {
        this._super(me.Sprite, "init", [x, y, {image: `menu_${i}`}]);
        this.pos.z = 4;
    }
});