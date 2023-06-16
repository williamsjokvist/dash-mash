<?php
include 'lib/commons/connect.php'; include 'lib/commons/session.php'; include 'lib/forum_functionality/time_elapse.php'; require_once 'lib/commons/packer.php';

ob_start();
include 'lib/commons/head.php';
echo '<title>Forum ~ Dash & Mash</title><link href="style/css/forum.css" rel="stylesheet"/><script src="//dashmash.ddns.net/script/desktop.js" defer></script></head>';
$content = ob_get_contents();
ob_end_clean();
echo PackContent($content) . "<body>";

include 'lib/commons/header.php'; include 'lib/commons/nav.php';

/*
 * Function that retrieves multiple values from boards and the topics and posts that where posted in that board
 * If user level is 1 a button will be shown to let the admin remove boards
 * Boards with level 0 are normal boards where any user can create new topics and posts
 * Boards with level 1 are boards where only admins can create new topics, but users can still post in admin made topics
 * 
 */

function addBoard($level, $dbconnect, $user_log_level) {

    if ($level == 0) {
        echo "<h3>Dash & Mash</h3><ul>";
    } else if ($level == 1) {
        echo "<h3>Admins' HQ</h3><ul title='admin'>";
    }
    echo "<li style='height:30px'><div>Boards:</div><div>Topics:</div><div class='reply'>Latest Reply: </div>";

    if (isset($_SESSION['logged_in']) && $user_log_level == 1) {
        echo "<div class='remove'>Remove</div>";
    }
    $stmt_board = $dbconnect->prepare("SELECT board_id, board_title, board_description FROM boards WHERE board_level = $level AND board_display = 1 ORDER BY `boards`.`board_title` ASC") or die($dbconnect->error);
    $stmt_board->execute();
    $result_board = $stmt_board->get_result();
    $stmt_board->close();
    if (mysqli_num_rows($result_board) > 0) {
        while ($row_board = $result_board->fetch_assoc()) {
            $board_id = $row_board["board_id"];
            $board_title = $row_board["board_title"];
            $board_description = $row_board["board_description"];
            $result_topic_amount = $dbconnect->query("SELECT * from topics WHERE topic_board_id = $board_id AND topic_display = 1");
            $topic_amount = mysqli_num_rows($result_topic_amount);

            $stmt_topic_and_post = $dbconnect->prepare("SELECT topics.topic_id, topics.topic_subject, posts.post_date, `user-accounts`.user_name FROM posts "
                    . "INNER JOIN topics ON posts.post_topic_id = topics.topic_id "
                    . "INNER JOIN `user-accounts` ON posts.post_author_id = `user-accounts`.user_id "
                    . "WHERE topics.topic_board_id = ? AND topics.topic_display = 1 AND posts.post_display = 1 ORDER BY posts.post_date DESC LIMIT 1") or die($dbconnect->error);
            $stmt_topic_and_post->bind_param('i', $board_id);
            $stmt_topic_and_post->execute();
            $result_topic_and_post = $stmt_topic_and_post->get_result();
            $stmt_topic_and_post->close();
            if (mysqli_num_rows($result_topic_and_post) > 0) {
                while ($row_topic_and_post = $result_topic_and_post->fetch_assoc()) {
                    $topic_id = $row_topic_and_post["topic_id"];
                    $topic_subject = $row_topic_and_post["topic_subject"];
                    $post_date = $row_topic_and_post["post_date"];
                    $user_name = $row_topic_and_post["user_name"];
                }
            }

            echo "<li><div><a href='//dashmash.ddns.net/forum/board.php?id=$board_id'>$board_title</a><span>$board_description</span></div>"
            . "<div>$topic_amount</div><div class='reply'>"
            . "In<a href='//dashmash.ddns.net/forum/topic.php?id=$topic_id'>$topic_subject</a>By<a href='//dashmash.ddns.net/profile.php?id=$user_name'>$user_name</a><time>" . time_elapsed_string($post_date, false) . "</time></div>";
            if (isset($_SESSION['logged_in']) && $user_log_level == 1) {
                echo "<div class='remove'><a href='removeboard.php?board=$board_id' class='remove'>&#8854;</a></div>";
            } else {
                echo "</li>";
            }
        }
        echo "</ul>";
    }
}
?>
<main>
    <section class = 'forum'>
        <header class="parallax">
            <h2>Boards</h2>
            <ol class = 'breadcrumbs'>
                <li><a href = 'forum.php'>Index</a></li>
            </ol>
        </header>
        <div>
            <?php
            if (isset($_SESSION['logged_in']) && $user_log_level == 1) {
                echo "<div class='link-row'><a href='https://dashmash.ddns.net/forum/createboard.php' class='fat'>Create new Board</a></div>";
            }
            addBoard(0, $dbconnect, $user_log_level);
            addBoard(1, $dbconnect, $user_log_level);
            ?>
        </div>
    </section>
    <?php
    include 'lib/commons/page-footing.php'; echo '</main>'; include 'lib/commons/footer.php'; echo '</body></html>';
    $dbconnect->close();