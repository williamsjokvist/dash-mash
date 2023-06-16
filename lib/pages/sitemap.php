<title>Dash & Mash ~ Sitemap</title>
</head>
<body>
    <?php
    include 'lib/commons/header.php';
    include 'lib/commons/nav.php';
    ?>
    <main class="sitemap">
        <header><h1>Sitemap</h1></header>
        <section>
            <h2>The Game</h2>
            <ul>
                <li><a href="//dashmash.ddns.net/?action=play">Play</a></li>
                <li><a href="//dashmash.ddns.net/game.php">Play (Fullsize)</a></li>
                <li><a href="//dashmash.ddns.net/?action=terms">Terms & Conditions</a></li>
                <li><a href="//dashmash.ddns.net/?action=about">About</a></li>
            </ul>
        </section>
        <section>
            <h2>Community</h2>
            <ul>
                <?php if ($user_log_id) {echo "<li><a href='//dashmash.ddns.net/profile.php?id=$user_log_name'>Your Profile</a></li>";}?>
                <li><a href="//dashmash.ddns.net/forum.php">The Forums</a></li>
                <li><a href="//dashmash.ddns.net/?action=leaderboards">Highscore Leaderboards</a></li>
            </ul>
        </section>
        <?php
        include 'lib/commons/page-footing.php';
        ?>
    </main>