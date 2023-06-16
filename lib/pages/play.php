<title>Dash & Mash ~ Playing</title>

<link rel="manifest" href="game/manifest.json">
<link href="style/css/game.css" rel="stylesheet"/>
<script src="script/game-window-optionality.js" defer></script>
<script src="//dashmash.ddns.net/script/desktop.js" defer></script>
</head>
<body>
    <div class="moody"></div>
    <?php
    include 'lib/commons/header.php';
    include 'lib/commons/nav.php';
    ?>
    <main class='play'>
        <header class="parallax">
            <div>
                <h2>NOTE</h2>
                <p>The site runs a developer version of the game, in other words, the game runs uncompiled. Because of this, all users are expected to run into consistent problems booting the game. <br><br>If the game shows the loading bar with only a black background – <a href="//dashmash.ddns.net/dump/boot-error.jpg" target="_blank">like this</a> – you need to normal reload the page <span style='color:#FF5F85;'>(by pressing <span class='alert'>F5</span>)</span> for the game to boot properly.</p>
            </div>
        </header>
        <section id="screen">
            <div>
                <iframe src="game.php" ></iframe>
                <?php require_once "lib/sections/chat.php"; ?>
            </div>
        </section>
        <section>
            <control-panel><button type="button">Expand</button><button type="button" onclick="window.open('//dashmash.ddns.net/game.php');">External</button><button type="button">Lights Off</button></control-panel>
            <details>
                <summary>Controls</summary>
                <dl>
                    <dt>Keyboard Controls</dt>
                    <dd><kbd>↑</kbd><kbd>↓</kbd><kbd>←</kbd><kbd>→</kbd><span> — Movement</span></dd>
                    <dd><kbd>Space</kbd><span> — Jump</span></dd>
                    <dd><kbd>X</kbd><span> — Slide</span></dd>
                </dl>
                <dl>
                    <dt>Pad Controls (XInput)</dt>
                    <dd><kbd>↑</kbd><kbd>↓</kbd><kbd>←</kbd><kbd>→</kbd><span> — Movement</span></dd>
                    <dd><kbd>A</kbd><span> — Jump</span></dd>
                    <dd><kbd>X</kbd><span> — Slide</span></dd>
                </dl>
            </details>
        </section><?php include 'lib/commons/page-footing.php'; ?></main>