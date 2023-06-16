
<?php
include '../lib/commons/connect.php';
include '../lib/commons/session.php';
require_once '../lib/commons/packer.php';

$board_id = filter_input(INPUT_GET, "board", FILTER_SANITIZE_NUMBER_INT);

$result_board = $dbconnect->query("SELECT board_title, board_level FROM boards WHERE board_id = $board_id");
if ($result_board->num_rows > 0) {
    while ($row_board = $result_board->fetch_assoc()) {
        $board_title = $row_board['board_title'];
        $board_level = $row_board['board_level'];
    }
}

if ($board_level == 1 && $user_log_level != 1 || empty($board_id) || empty($board_title)) {
    header("Location: https://dashmash.ddns.net/?action=error"); /* Redirects error page if non admin tries to post in admin boards or board is empty */
}

ob_start();
include '../lib/commons/head.php';

echo '<title>Creating Topic in $board_title</title><link href="../style/css/forum.css" rel="stylesheet"/><script src="//dashmash.ddns.net/script/desktop.js" defer></script></head>';
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
                <h2>Create Topic</h2>
                <ol class="breadcrumbs">
                    <?php
                    echo "<li><a href = '../forum.php'>Index</a> ›</li>";
                    echo "<li><a href = 'board.php?id=$board_id'>$board_title</a> ›</li>";
                    echo "<li><em>Creating Topic in <strong>$board_title</strong></em></li>";
                    ?>
                </ol>
            </header>
            <div>
                <?php echo "<form method='POST' action='posttopic.php?board=$board_id' id='topic-reply'>"; ?>
                <input type="text" name="subject" placeholder="Subject ">
                <fieldset>
                    <textarea name="content" rows="10" cols="95" tabindex="0" maxlength="10000" placeholder="Your Message"></textarea>
                    <div><span>Supports <a href="https://www.phpbb.com/community/help/bbcode#f0r0">BBCode</a></span><button type="submit">Post</button></div>
                </fieldset>
                </form>
            </div>
        </section>
        <?php include '../lib/commons/page-footing.php'; ?></main><?php include '../lib/commons/footer.php'; ?></body></html>