
/* Fetch user */
var rq = new XMLHttpRequest();
rq.open('GET', 'https://dashmash.ddns.net/lib/game/serv.php', true);

rq.onload = function () {
    if (rq.status >= 200 && rq.status < 400) {

        var data = JSON.parse(rq.responseText);
        this.username = `${data.user_name}`;
        this.userid = `${data.user_id}`;
        this.score = `${data.rank_score}`;
        this.userimg = `${data.profile_avatar}`;
        console.log("%cYou are logged in as: " + this.username, 'background: #222; color: #fff; font-size:16px;');
    }
};

rq.onerror = function () {
    console.log("%cCouldn't connect to server :(", 'background: #000; color: red; font-size:16px;');
};

rq.send();

/* Game namespace */
var game = {
    data: {
        /*Store info*/
        score: 0,
        level: 0
    },

    onload: function () {

        if (me.video.init(1280, 720, {
            renderer: me.video.CANVAS,
            scale: "auto",
            scaleMethod: "fit",
            doubleBuffering: true,
            antiAlias: true
        })){
            document.getElementsByTagName("canvas")[0].setAttribute("tabindex", "0");
        }

        if (me.game.HASH.debug === true) {
            me.device.onReady(function () {
                me.plugin.register.defer(this, me.debug.Panel, "debug", me.input.KEY.V);
            });
        }

        /* System */

        me.audio.init("mp3");
        me.sys.pauseOnBlur = false;
        me.sys.fps = 50;
        me.sys.updatesPerSecond = 50;

        if (me.sys.checkVersion("5.1.0") > 0) {
            console.error("melonJS is too old. Expected: 5.1.0, Got: " + me.version);
        }

        /*Loader*/

        me.loader.onload = this.loaded.bind(this);
        me.loader.preload(game.resources);

        me.state.change(me.state.LOADING);
    },

    loaded: function () {

        me.loader.load({name: "avatar", type: "image", src: `lib/user/uploads/avatar/${rq.userimg}`});

        me.state.set(me.state.MENU, new game.TitleScreen());
        me.state.set(me.state.PLAY, new game.PlayScreen());

        me.state.set(me.state.SCORE, new game.ScoringScreen());
        me.state.set(me.state.READY, new game.LevelScreen());
        me.state.set(me.state.SETTINGS, new game.SettingsScreen);
        me.state.set(me.state.LDRBOARD, new game.LeaderboardScreen);

        me.state.transition("fade", "#000", 250);

        me.pool.register("Player", game.PlayerEntity);
        //me.pool.register("Sword", game.SwordEntity, true);
        //game.player = me.game.world.getChildByName("Player")[0];
        me.pool.register("Enemy", game.EnemyEntity);
        //me.pool.register("Laser", game.LaserEntity);

        me.state.change(me.state.MENU);
        me.audio.play("sphere", true, null, .5);
        me.event.subscribe(me.event.KEYDOWN, function (action, keyCode) {

            if (keyCode === me.input.KEY.F) {
                if (!me.device.isFullscreen) {
                    me.device.requestFullscreen();
                } else {
                    me.device.exitFullscreen();
                }
            }
        });
    }
};

me.device.onReady(function () {
    game.onload();
});