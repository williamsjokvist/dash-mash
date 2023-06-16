game.ScoringScreen = me.ScreenObject.extend({

    onResetEvent: function () {
        me.audio.play("HIT", false, null, 1);

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "https://dashmash.ddns.net/lib/game/insert.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log(xhr.responseText);
            }
        };

        xhr.send(`score=${game.data.score}`);
        
        addGeneric();
        
        me.game.world.addChild(new (me.Renderable.extend({
            init: function () {
                this._super(me.Renderable, 'init', [0, 0, me.game.viewport.width, me.game.viewport.height]);
                this.font = new me.Font("Squada One", "5.5em", "#b80e5d", "center");
            },
            draw: function (renderer) {
                this.font.setFont("Squada One", "62px", "#fff");
                if (game.data.level === 0) {
                    this.font.draw(renderer, "You Cleared The Tutorial! ", me.game.viewport.width / 2, 350);
                } else if (game.data.level === 1) {
                    this.font.draw(renderer, "Congratulations on beating The Frostlands! ", me.game.viewport.width / 2, 350);
                } else if (game.data.level === 2) {
                    this.font.draw(renderer, "You sure press a lot of buttons ", me.game.viewport.width / 2, 350);
                }
                this.font.setFont("Visitor", "8.5em", "#fff");
                this.font.draw(renderer, "Score: ", me.game.viewport.width / 2 - 100, 450);
                this.font.setFont("Visitor", "8.5em", "#f90");
                this.font.draw(renderer, game.data.score, me.game.viewport.width / 2 + 300, 450);
            }
        })));

        setTimeout(function () {
            me.state.change(me.state.LDRBOARD);
            me.audio.fade("sphere", 0.0, .5, 3000);
            me.audio.resume("sphere");
        }, 7000);
    },

    onDestroyEvent: function () {
        
    },

    update: function () {
        return true;
    }
});