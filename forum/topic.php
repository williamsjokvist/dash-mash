<?php
include '../lib/commons/connect.php'; include '../lib/commons/session.php'; include '../lib/forum_functionality/time_elapse.php'; require_once '../lib/commons/packer.php';

$topic_id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

$stmt_topic = $dbconnect->prepare("SELECT boards.board_title, topics.topic_subject, topics.topic_display, topics.topic_board_id FROM topics INNER JOIN boards ON topics.topic_board_id = boards.board_id WHERE topic_id = ?");
$stmt_topic->bind_param('i', $topic_id);
$stmt_topic->execute();
$res_topic = $stmt_topic->get_result();
if (mysqli_num_rows($res_topic) > 0) {
    while ($row_topic = $res_topic->fetch_assoc()) {
        $topic_subject = $row_topic["topic_subject"];
        $topic_display = $row_topic["topic_display"];
        $topic_board_id = $row_topic["topic_board_id"];

        if ($topic_display == 0) {
            header("Location: https://dashmash.ddns.net/?action=locked"); /* Redirects to locked alert page if thread has been locked */
        }
        $topic_board_name = $row_topic["board_title"];
    }
} else {
    header("Location: https://dashmash.ddns.net/?action=error"); /* Redirects to error page if topic doesn't exist */
}
?>

<?php
ob_start();
echo "<title>$topic_subject ~ Dash & Mash</title>";
include '../lib/commons/head.php';
echo '<link href="../style/css/forum.css" rel="stylesheet"/><script src="//dashmash.ddns.net/script/desktop.js" defer></script></head>';
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
        <section class="forum">
            <header class="parallax"><h2><?php echo $topic_subject; ?></h2>
                <ol class="breadcrumbs">
                    <?php
                    echo "<li><a href = '../forum.php'>Index</a> ›</li>";
                    echo "<li><a href = 'board.php?id=$topic_board_id'>$topic_board_name</a> ›</li>";
                    echo "<li><a href='topic.php?id=$topic_id'>$topic_subject</a></li>";
                    ?>
                </ol>
            </header>
            <div>
                <?php
                if ($user_log_id) {
                    /* If logged in */
                    echo "<div class='link-row'>";
                    if ($user_log_level != 10) {
                        echo "<a href='#topic-reply' class='fat'>Respond to Topic</a>";
                    }
                    if ($user_log_level == 1) {
                        echo "<a href='removetopic.php?topic=$topic_id&board=$topic_board_id' class='fat'>Remove Topic</a>";
                    }
                    echo "</div>";
                }
                ?>
                <?php
                $results_per_page = 15;  /* Maximum amount of results per page */

                $result = $dbconnect->query("SELECT * FROM posts WHERE post_topic_id= $topic_id");
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

                /* Fetch posts */
                $stmt_posts = $dbconnect->prepare("SELECT posts.*, `user-accounts`.user_name, `user-accounts`.user_level, `user-accounts`.user_joindate, `user-accounts`.user_online, `user-profile`.profile_avatar FROM `posts` INNER JOIN `user-accounts` ON `posts`.post_author_id = `user-accounts`.user_id INNER JOIN `user-profile` ON `posts`.post_author_id = `user-profile`.profile_user_id WHERE post_topic_id= $topic_id LIMIT $starting_limit_number, $results_per_page") or die($dbconnect->error);
                $stmt_posts->execute();
                $res_posts = $stmt_posts->get_result();

                $stmt_posts->close();
                if (mysqli_num_rows($res_posts) > 0) {
                    echo "<ol>";
                    while ($row = $res_posts->fetch_assoc()) {
                        $post_id = $row["post_id"];
                        $post_date = $row['post_date'];
                        $post_content = $row['post_content'];
                        $post_author_id = $row["post_author_id"];
                        $post_display = $row["post_display"];
                        $user_name = $row["user_name"];
                        $user_level = $row["user_level"];
                        $user_joindate = new DateTime($row["user_joindate"]);
                        $user_online = $row["user_online"];
                        $user_avatar = $row["profile_avatar"];

                        if ($post_display == 1) {
                            /* Only display post if valued 1 */
                            echo "<li><article><header>";
                            if (isset($_SESSION["logged_in"]) && $user_log_level == 1) {
                                echo "<a href='removepost.php?post=$post_id&topic=$topic_id&board=$topic_board_id' class='remove'>&#8854;</a>";
                            }

                            /* USER_LEVEL */
                            if ($user_level == 1) {
                                echo "<span>Administrator</span>";
                            } else if ($user_level == 2) {
                                echo "<span style='color:#ae25c5;'>Community Manager</span>";
                            } else if ($user_level == 10) {
                                echo "<span style='color:#d41d1d; text-transform: uppercase; font-weight: bold;'>Banned</span>";
                            }

                            echo "<div><a href='//dashmash.ddns.net/profile.php?id=$user_name'><img src='//dashmash.ddns.net/lib/user/uploads/avatar/$user_avatar' alt='avatar'/>";

                            /* Username and online status */
                            echo "<span>$user_name</span><div class='user-online' style='background-color:";
                            if ($user_online == 1) {
                                echo "#10e910";
                            } else if ($user_online == 0) {
                                echo "#ec2a24";
                            }
                            echo "'></div></a></div>";

                            /* /////////////////////////// POSTER  INFO/ //////////////////////// */

                            $stmt_post_count = $dbconnect->prepare("SELECT post_id FROM `posts` WHERE post_author_id = ?");
                            $stmt_post_count->bind_param('s', $post_author_id);
                            $stmt_post_count->execute();
                            $result_post_count = $stmt_post_count->get_result();
                            $stmt_post_count->close();

                            /* USER_LEVEL */
                            echo "<ul>";
                            if ($user_level == 1) {
                                echo "<li><span style='display:none;'>Administrator</span></li>";
                            } else if ($user_level == 10) {
                                echo "<li><span style='color:#d41d1d; display:none; text-transform: uppercase;'>Banned</span></li>";
                            }

                            echo "<li><span>Joined:</span>" . $user_joindate->format('d M Y') . "</li>"
                            . "<li><span>Posts:</span>" . mysqli_num_rows($result_post_count) . "</li></ul></header>"
                            . "<p>$post_content</p><footer><time>" . time_elapsed_string($post_date, false) . "</time></footer></article></li>";
                        }
                    }
                    echo "</ol>";
                } else {
                    echo "<span style='display:block;text-align:center;margin:20px 0;'>No posts could be found</span>";
                }

                echo "<div class='link-row'>";

                $current_page = $page; /* Set current page to $_GET["page"] */

                for ($page = 1; $page <= $number_of_pages; $page++) {
                    if ($page == $current_page) {
                        echo "<b>$page</b>";
                    } else {
                        /* Regular */
                        echo "<a href='topic.php?id=$topic_id&board=$topic_board_id&page=$page'>$page</a>";
                    }
                }
                echo "</div>";
                ?>
                <?php
                if (isset($_SESSION["logged_in"]) && !empty($_SESSION["logged_in"]) && !$_SESSION["logged_in"] == 0 && $user_log_level != 10) {
                    echo "<form action='postreply.php?id=$topic_id&board=$topic_board_id' method='POST' id=topic-reply>";
                    echo "<div><span>You are logged in as: <a href='//dashmash.ddns.net/profile.php?id=$user_log_name'>$user_log_name</a></span><a href='javascript:void(0)' type='toggler'>BBCode List</a><dl data-toggled='false'><dt>Supported BBCode</dt><dd> [b] <b>bold</b> [/b] </dd><dd>[i] <i>italic</i> [/i]</dd><dd>[u] <u>underlined</u> [/u]</dd><dd>[quote] <pre>quote</pre> [/quote]</dd><dd>[size=*]<span style='font-size:15px;'> text </span>[/size] <small>(defined in pixels eg. 12px)</small></dd><dd>[color=*]<span style='color:peachpuff;'> color </span>[/size] <small>(HEX color codes)</small></dd><dd>[url]<a> link </a>[/url]</dd><dd>[video] url [/video] <small>(webm)</small></dd><dd>[audio] url [/audio] <small>(mp3 | wav | ogg)</small></dd><dd>[img] url [/img] <small>(jpg | gif |png | bmp)</small></dd><dd>[yt]*[/yt]<small>(YouTube Video ID)</small></dd></dl></div>";
                    echo "<fieldset><legend>Post Reply</legend><textarea name='content' rows='10'  cols='100' tabindex='1' maxlength='10000'></textarea><div><span></span><button type='submit'>Post</button></div></fieldset>";
                }
                if ($user_log_level == 10) {
                    echo "<p>You are not allowed to post in the forum.</p>";
                }
                echo "</form>";
                ?>
            </div>
        </section>
        <?php
        include '../lib/commons/page-footing.php'; echo '</main>'; include '../lib/commons/footer.php'; echo '</body></html>';
        $dbconnect->close();