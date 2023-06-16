<?php
include '../lib/commons/connect.php'; include '../lib/commons/session.php'; include '../lib/forum_functionality/time_elapse.php'; require_once '../lib/commons/packer.php';

$board_id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

$stmt_board = $dbconnect->prepare("SELECT board_title, board_level, board_display FROM boards WHERE board_id = ?") or die($dbconnect->error);
$stmt_board->bind_param('i', $board_id);
$stmt_board->execute();
$stmt_board->bind_result($board_title, $board_level, $board_display);
$result_board = $stmt_board->get_result();
$stmt_board->close();
if (mysqli_num_rows($result_board) > 0) {
    while ($row_board = $result_board->fetch_assoc()) {
        $board_title = $row_board["board_title"];
        $board_level = $row_board["board_level"];
        $board_display = $row_board["board_display"];
    }
} else if (mysqli_num_rows($result_board) < 0 || $board_display == 0) {
    header("Location: https://dashmash.ddns.net/?action=error"); /* Redirects to error page if board doesn't exist or has been locked */
}
?>

<?php
ob_start();
echo "<head><title>$board_title ~ Dash & Mash</title>";
include '../lib/commons/head.php';
echo '<link href="../style/css/forum.css" rel="stylesheet"/><script src="//dashmash.ddns.net/script/desktop.js" defer></script></head>';
$content = ob_get_contents();
ob_end_clean();
echo PackContent($content);
?>

<body>
    <?php
    include '../lib/commons/header.php'; include '../lib/commons/nav.php';
    ?>
    <main>
        <section class='forum'>
            <header class="parallax"><h2><?php echo "$board_title"; ?></h2>
                <ol class="breadcrumbs">
                    <?php
                    echo "<li><a href = 'https://dashmash.ddns.net/forum.php'>Index</a> ›</li>";
                    echo "<li><a href = 'board.php?id=$board_id'>$board_title</a> ›</li>";
                    ?>
                </ol>
            </header>
            <div>
                <?php
                if ($user_log_id) {
                    echo "<div class='link-row'>";
                    if ((isset($_SESSION["logged_in"]) && $user_log_level != 10 && $board_level != 1) || ($board_level == 1 && $user_log_level == $admin)) {
                        echo "<a href='createtopic.php?board=$board_id' class='fat'>Create new Topic</a>";
                    }
                    if (isset($_SESSION["logged_in"]) && $user_log_level == 1) {
                        echo "<a href='removeboard.php?board=$board_id' class='fat'>Remove Board</a>";
                    }
                    echo "</div>";
                }
                ?>
                <ul>
                    <li style="height: 30px;">
                        <div>Topics:</div>
                        <div class="posts">Posts:</div>
                        <div class='reply'>Latest Reply:</div>
                        <?php
                        if (isset($_SESSION['logged_in']) && $user_log_level == 1) {
                            echo "<div class='remove'>Remove</div>";
                        }
                        ?>
                    </li>
                    <?php
                    $results_per_page = 10;  /* Maximum amount of results per page */

                    $result = $dbconnect->query("SELECT * FROM topics WHERE topic_board_id= $board_id AND topic_display = 1");
                    $number_of_results = mysqli_num_rows($result); /* Amount of results in query */

                    /* Determine how many pages are required */
                    $number_of_pages = ceil($number_of_results / $results_per_page);

                    /* Determine which page user is on */
                    if (!isset($_GET["page"])) {
                        $page = 1;
                    } else {
                        $page = $_GET["page"];
                    }

                    /* Determine the starting limit number of query */
                    $starting_limit_number = ($page - 1) * $results_per_page;


                    /* Retrieve selected results and display */
                    $stmt_topic = $dbconnect->prepare("SELECT topics.topic_id, topics.topic_date, topics.topic_subject, `user-accounts`.user_name FROM posts "
                            . "INNER JOIN topics ON posts.post_date = topics.topic_date "
                            . "INNER JOIN `user-accounts` ON posts.post_author_id = `user-accounts`.user_id WHERE topic_board_id = ? AND topic_display = 1 ORDER BY topic_id DESC LIMIT ?, ?") or die($dbconnect->error);
                    $stmt_topic->bind_param('iii', $board_id, $starting_limit_number, $results_per_page);
                    $stmt_topic->execute();
                    $stmt_topic->bind_result($topic_id, $topic_date, $topic_subject, $topic_author_username);
                    $result_topic = $stmt_topic->get_result();
                    $stmt_topic->close();
                    if (mysqli_num_rows($result_topic) > 0) {
                        while ($topic_row = $result_topic->fetch_assoc()) {
                            $topic_id = $topic_row["topic_id"];
                            $topic_date = new DateTime($topic_row["topic_date"]);
                            $topic_subject = $topic_row["topic_subject"];
                            $topic_author_username = $topic_row["user_name"];

                            $result_post_number = $dbconnect->query("SELECT post_id FROM posts WHERE post_topic_id = $topic_id AND post_display = 1");
                            $post_number = mysqli_num_rows($result_post_number);

                            $stmt_latest_post = $dbconnect->prepare("SELECT posts.post_date, `user-accounts`.user_name FROM posts "
                                    . "INNER JOIN `user-accounts` ON posts.post_author_id = `user-accounts`.user_id WHERE post_topic_id = ? AND post_display = 1 ORDER BY posts.post_date DESC LIMIT 1") or die($dbconnect->error);
                            $stmt_latest_post->bind_param('i', $topic_id);
                            $stmt_latest_post->execute();
                            $stmt_latest_post->bind_result($latest_post_date, $latest_post_username);
                            $result_latest_post = $stmt_latest_post->get_result();
                            $stmt_latest_post->close();
                            if (mysqli_num_rows($result_latest_post) > 0) {
                                while ($row_latest_post = $result_latest_post->fetch_assoc()) {
                                    $latest_post_date = $row_latest_post["post_date"];
                                    $latest_post_username = $row_latest_post["user_name"];

                                    echo "<li><div><a href='topic.php?id=$topic_id'>$topic_subject</a><div>By <a href='//dashmash.ddns.net/profile.php?id=$topic_author_username'>$topic_author_username,</a><time>";
                                    echo $topic_date->format('d, M Y');
                                    echo "</time></div></div><div class='posts'>$post_number</div><div class='reply'>By<a href='//dashmash.ddns.net/profile.php?id=$latest_post_username'>$latest_post_username</a><time>" . time_elapsed_string($latest_post_date, false) . "</time></div>";

                                    if (isset($_SESSION['logged_in']) && $user_log_level == 1) {
                                        echo "                    
                                            <div class='remove'>
                                            <a href='removetopic.php?topic=$topic_id&board=board_id' class='remove'>&#8854;</a></div></li>";
                                    } else {
                                        echo "</li>";
                                    }
                                }
                            }
                        }
                    }
                    ?>
                </ul>
                <?php
                echo "<div class='link-row'>";

                $current_page = $page; /* Set current page to $_GET["page"] */

                for ($page = 1; $page <= $number_of_pages; $page++) {
                    if ($page == $current_page) {
                        echo "<b>$page</b>";
                    } else {
                        /* Regular */
                        echo "<a href='board.php?id=$board_id&page=$page'>$page</a>";
                    }
                }

                echo "</div>";
                ?>
            </div>

        </section>
        <?php
        include '../lib/commons/page-footing.php'; echo '</main>'; include '../lib/commons/footer.php'; echo '</body></html>';
        $dbconnect->close();
        