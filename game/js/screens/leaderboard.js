
function addRank(user, score, avatar, i) {
    me.game.world.addChild(new (me.Sprite.extend({
        /*Add BG*/
        init: function () {
            this._super(me.Sprite, "init", [(me.game.viewport.width / 2 + 95), (182 + (i * 51)), {image: `rank`}]);
            this.alpha = 0;
            this.tween = new me.Tween(this).to({alpha: 1}, 1000);
            this.tween.start();
            this.animationpause = true;
            this.pos.z = 4;
        }
    })));

    me.game.world.addChild(new (me.Renderable.extend({
        /*Add text*/
        init: function () {
            this._super(me.Renderable, 'init', [0, 0, me.game.viewport.width, me.game.viewport.height]);
            this.font = new me.Font("Visitor", 42, "#F90", "left");
            this.font.alpha = 0;
            this.tween = new me.Tween(this.font).to({alpha: 1}, 1000);
            this.tween.start();
            this.pos.z = 7;
        },

        draw: function (renderer) {
            this.font.setFont("Visitor", 42, "#dea037", "left");
            this.font.draw(renderer, "#" + (i + 1), me.game.viewport.width - 300, 520 + i * 51.5);
            this.font.setFont("Visitor", 42, "#1b1b1b", "left");
            this.font.draw(renderer, score, me.game.viewport.width + 215, 520 + i * 51.5);
            this.font.setFont("Visitor", 42, "#fff", "left");
            this.font.draw(renderer, user, me.game.viewport.width - 160, 520 + i * 51.5);
        }
    })));
}

game.LeaderboardScreen = me.ScreenObject.extend({

    onResetEvent: function () {

        let rnkrq = new XMLHttpRequest();
        rnkrq.open('GET', 'https://dashmash.ddns.net/lib/game/ranks.php', true);

        rnkrq.onload = function () {
            if (rnkrq.status >= 200 && rnkrq.status < 400) {
                this.data = JSON.parse(rnkrq.responseText);

                for (let i = 0; i < this.data.rankings.length; i++) {
                    addRank(this.data.rankings[i][1].user_name, this.data.rankings[i][0].rank_score, this.data.rankings[i][2], i);
                }
            }
        };

        rnkrq.onerror = function () {
            console.log("%cCouldn't connect to database server :(", 'background: #000; color: red; font-size:16px;');
        };
        rnkrq.send();

        this.handler = me.event.subscribe(me.event.KEYDOWN, this.keyHandler.bind(this));

        addGeneric();
        addSub();

        me.game.world.addChild(new game.GUI.Heading(1));
    },
    keyHandler: function (action, keyCode, edge) {
        if (action === "back" || action === "slide") {
            me.state.change(me.state.MENU);
        }
    },
    onDestroyEvent: function () {
        me.event.unsubscribe(this.handler);
    },

    update: function () {
        return true;
    }
}
);