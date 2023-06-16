
<?php
include '../lib/commons/connect.php';
include '../lib/commons/session.php';
require_once '../lib/commons/packer.php';


if ($user_log_level != $admin) {
    header("Location: https://dashmash.ddns.net/?action=error"); /* Redirects error page if non admin tries to post in admin boards or board is empty */
}


ob_start();
include '../lib/commons/head.php';
echo '<title>Creating Board</title><link href="../style/css/forum.css" rel="stylesheet"/><script src="//dashmash.ddns.net/script/desktop.js" defer></script></head>';
$content = ob_get_contents();
ob_end_clean();
echo PackContent($content);
?>
</head>
<body>
    <?php
    include '../lib/commons/header.php';
    include '../lib/commons/nav.php';
    ?>
    <main>
        <section class="forum create"><header class="parallax">
                <h2>Create Board</h2>
                <ol class="breadcrumbs">
                    <?php
                    echo "<li><a href = '../forum.php'>Index</a> ›</li>";
                    echo "<li><em>Creating Board <strong></strong></em></li>";
                    ?>
                </ol>
            </header>
            <div>
                <?php echo "<form method='POST' action='postboard.php' id='topic-reply' class='board'>"; ?>
                <input type="number" name="boardlevel" placeholder="0 / 1" min='0' max='1'/>
                <input type="text" name="boardname" placeholder="Board Name"/>
                <button type="submit">Create</button>
                </form>
            </div>
        </section>
        <?php include '../lib/commons/page-footing.php'; ?>
    </main>
    <?php include '../lib/commons/footer.php'; ?>
</body>
</html>