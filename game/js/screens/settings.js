
let arr = [0, 1, 2];

game.SettingsScreen = me.ScreenObject.extend({

    onResetEvent: function () {

        this.handler = me.event.subscribe(me.event.KEYDOWN, this.keyHandler.bind(this));
        me.game.world.addChild(new game.GUI.Heading(2));

        if (me.audio.muted() === true) {
            this.muted = true;
        } else {
            this.muted = false;
        }

        addGeneric();
        addSub();
        this.selector = new game.SettingsScreen.Selector();
        this.selectorIndex = 0;
        this.selector.setItem(this.selectorIndex);
        me.game.world.addChild(this.selector);

        this.menus = [0, 1, 2];
        for (let i = 0; i < this.menus.length; i++) {
            me.game.world.addChild(new game.SettingsScreen.Item(me.game.viewport.width / 2, (i * 120 + 400), i));
        }

        this.checks = [0, 1, 2];
        for (let i = 0; i < this.checks.length; i++) {
            this.checks[i] = me.game.world.addChild(new game.GUI.Check(me.game.viewport.width / 2 + 200, (i * 120 + 400)));
        }

        /*Init checks*/
        if (this.muted === false)
            this.check(1, true);
        if (document.body.classList.contains("scanlines"))
            this.check(2, true);
        if (me.device.isFullscreen)
            this.check(0, true);

    },
    keyHandler: function (action, keyCode, edge) {
        if (action === "up") {
            this.moveSelector(-1);
        } else if (action === "down") {
            this.moveSelector(1);
        } else if (action === "back" || action === "slide") {
            me.state.change(me.state.MENU);
        } else if (action === "enter" || action === "jump") {

            if (this.selectorIndex === 0) {
                /*Fullscreen*/
                if (!me.device.isFullscreen) {
                    me.device.requestFullscreen();
                    this.check(0, true);
                } else {
                    me.device.exitFullscreen();
                    this.check(0, false);
                }

            } else if (this.selectorIndex === 1) {
                /*Volume*/
                if (this.muted === false) {
                    me.audio.muteAll();
                    this.muted = true;
                    this.check(1, true);
                } else {
                    me.audio.unmuteAll();
                    this.muted = false;
                    this.check(1, false);
                }
            } else if (this.selectorIndex === 2) {
                /*Scanlines*/
                document.body.classList.toggle("scanlines");
                if (document.body.classList.contains("scanlines")) {
                    this.check(2, true);
                } else {
                    this.check(2, false);
                }
            }

            me.state.change(me.state.SETTINGS);
            me.audio.play("UI_START", false, null, 1);
        }
    },
    moveSelector: function (amount) {
        this.selectorIndex = (this.selectorIndex + amount) % this.menus.length;
        if (this.selectorIndex < 0) {
            this.selectorIndex = this.menus.length - 1;
        }

        this.selector.setItem(this.menus[this.selectorIndex]);
    },
    check: function (index, toggle) {
        /*Handles checkmark to toggled setting*/

        if (toggle === true) {
            if (!this.checks[index].isCurrentAnimation("check")) {
                this.checks[index].setCurrentAnimation("check");
                console.log("set");
            }
        } else {
            if (!this.checks[index].isCurrentAnimation("uncheck")) {
                this.checks[index].setCurrentAnimation("uncheck");
                console.log("set uncheck");
            }
        }
    },

    onDestroyEvent: function () {
        me.event.unsubscribe(this.handler);
        me.game.world.removeChild(this.selector);
    },
    update: function () {
        return true;
    }
});
game.SettingsScreen.Selector = me.Sprite.extend({
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
game.SettingsScreen.Item = me.Sprite.extend({
    init: function (x, y, i)
    {
        this._super(me.Sprite, "init", [x, y, {image: `setting_${i}`}]);
        this.pos.z = 4;
    }
});