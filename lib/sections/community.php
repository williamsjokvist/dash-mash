<section class='community parallax'>
    <header><h2>Community</h2></header>
    <div>
        <section class="forumac">
            <h3>Latest Forum Activity</h3>
            <ol>
                <?php
                include 'lib/forum_functionality/time_elapse.php';  
                // FUKING SUPER VIKTIG QUERY NEDAN PLS NO REMOVE
                /*SELECT posts.*, topics.*, `user-accounts`.* FROM posts INNER JOIN topics ON topics.topic_id = posts.post_topic_id INNER JOIN `user-accounts` ON `user-accounts`.user_id = posts.post_author_id WHERE posts.post_id BETWEEN 0 AND (SELECT MAX(posts.post_id) FROM posts) AND topics.topic_display = 1 AND posts.post_display = 1 ORDER BY posts.post_date DESC*/
                /*SELECT boards.board_title, topics.topic_id, topics.topic_board_id, topics.topic_subject, topics.topic_date, posts.post_date FROM topics INNER JOIN posts ON posts.post_topic_id = topics.topic_id INNER JOIN boards ON boards.board_id = topics.topic_board_id WHERE posts.post_display = 1 AND topics.topic_display = 1 AND boards.board_display = 1 ORDER BY `posts`.`post_date` DESC LIMIT 5*/
                
                /*Gets the name of the board, topic id, subject, board id, and what time it was created, and when the date of the latest post in that topic*/
                $stmt_post = $dbconnect->prepare("SELECT posts.post_id, posts.post_date, topics.topic_date, topics.topic_subject, topics.topic_id, boards.board_id, boards.board_title FROM topics INNER JOIN posts ON topics.topic_id = posts.post_topic_id INNER JOIN boards ON topics.topic_board_id = boards.board_id WHERE posts.post_id BETWEEN 0 AND (SELECT MAX(posts.post_id) FROM posts) AND topics.topic_display = 1 AND posts.post_display = 1 ORDER BY posts.post_date DESC LIMIT 5") or die($dbconnect->error);
                $stmt_post->execute();
                $stmt_post->bind_result($post_id, $post_date, $topic_date, $topic_subject, $topic_id, $board_id, $board_title);
                $result_post = $stmt_post->get_result();
                $stmt_post->close();
                if(mysqli_num_rows($result_post) > 0){
                    while ($row_post = $result_post->fetch_assoc()) {
                        $post_id = $row_post["post_id"];
                        $post_date = $row_post["post_date"];
                        $topic_date = new DateTime($row_post["topic_date"]);
                        $topic_subject = $row_post["topic_subject"];
                        $topic_id = $row_post["topic_id"];
                        $board_id = $row_post["board_id"];
                        $board_title = $row_post["board_title"];
                        /*Gets the name of the user who created the topic with inserted topic_id*/
                        
                        
                        $stmt_topic_author = $dbconnect->prepare("SELECT `user-accounts`.`user_name` FROM (SELECT posts.post_author_id FROM topics INNER JOIN posts ON posts.post_topic_id = ? ORDER BY posts.post_date ASC LIMIT 5) AS lastuser INNER JOIN `user-accounts` ON `user-accounts`.`user_id` = lastuser.post_author_id") or die($dbconnect->error);
                        $stmt_topic_author->bind_param('i', $topic_id);
                        $stmt_topic_author->execute();
                        $stmt_topic_author->bind_result($topic_author);
                        $result_topic_author = $stmt_topic_author->get_result();
                        $stmt_topic_author->close();
                        if (mysqli_num_rows($result_topic_author) > 0){
                            while ($row_topic_author = $result_topic_author->fetch_assoc()) {
                                $topic_author = $row_topic_author["user_name"];
                            }
                        }
                        /*Gets the name of the user who last posted in a topic with inserted topic_id*/
                        $stmt_latest_post_user = $dbconnect->prepare("SELECT `user-accounts`.`user_name` FROM posts INNER JOIN `user-accounts` ON `user-accounts`.`user_id` = posts.post_author_id WHERE post_id = ?") or die($dbconnect->error);
                        $stmt_latest_post_user->bind_param('i', $post_id);
                        $stmt_latest_post_user->execute();
                        $stmt_latest_post_user->bind_result($latest_post_author);
                        $result_latest_post_user = $stmt_latest_post_user->get_result();
                        $stmt_latest_post_user->close();
                        if (mysqli_num_rows($result_latest_post_user) > 0){
                            while ($row_latest_post_user = $result_latest_post_user->fetch_assoc()) {
                                $latest_post_author = $row_latest_post_user["user_name"];
                            }
                        }
                        echo "<li><div><a href='//dashmash.ddns.net/forum/topic.php?id=$topic_id'>$topic_subject</a><small><a href='//dashmash.ddns.net/profile.php?id=$topic_author'>$topic_author</a><time>" . " - " . $topic_date->format('d M Y') . " - " . "</time><a href='//dashmash.ddns.net/forum/board.php?id=$board_id'>$board_title</a></small></div>"
                        . "<div>by<a href='//dashmash.ddns.net/profile.php?id=$latest_post_author'>$latest_post_author</a><time>" . time_elapsed_string($post_date, false) . "</time></div></li>";
                    }
                }
                ?>
            </ol>
            <footer><a href="forum.php">Community Forum</a></footer>
        </section>
        <section class="discord"></section>
    </div>
</section>