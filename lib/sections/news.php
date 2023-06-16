<section class="news parallax">
    <header><h2>Latest News</h2></header>
    <div>
        <article>
            <?php
            
            $stmt_topic = $dbconnect->prepare("SELECT topics.topic_id, topics.topic_subject, topics.topic_date, posts.post_content, `user-accounts`.`user_name`, `user-profile`.`profile_avatar` FROM posts "
                    . "INNER JOIN topics ON posts.post_topic_id = topics.topic_id "
                    . "INNER JOIN `user-accounts` ON `user-accounts`.`user_id` = posts.post_author_id "
                    . "INNER JOIN `user-profile` ON `user-profile`.`profile_user_id` = posts.post_author_id "
                    . "WHERE topics.topic_board_id = 5 AND topics.topic_display = 1 ORDER BY topics.topic_date DESC LIMIT 1") or die($dbconnect->error);
            $stmt_topic->execute();
            $stmt_topic->bind_result($topic_title, $topic_date, $topic_id, $post_content, $user_name, $post_author_avi);
            $result_topic = $stmt_topic->get_result();
            $stmt_topic->close();
            if(mysqli_num_rows($result_topic) > 0){
                while ($row_topic = $result_topic->fetch_assoc()) {
                    $topic_title = $row_topic['topic_subject'];
                    $topic_date = new DateTime($row_topic['topic_date']);
                    $topic_id = $row_topic['topic_id'];
                    $post_content = $row_topic["post_content"];
                    $user_name = $row_topic["user_name"];
                    $post_author_avi = $row_topic["profile_avatar"];
                    
                    echo "<header><img src='//dashmash.ddns.net/lib/user/uploads/avatar/$post_author_avi' alt='avatar'><h3>$topic_title</h3><span>Date: <strong>"
                        . $topic_date->format('d-m-Y')
                        . "</strong> Author: <strong>$user_name</strong></span><a href='https://dashmash.ddns.net/forum/topic.php?id=$topic_id' class='fat'>Go to article</a></header>"
                        . "<p>$post_content</p>";
                }
            }
            ?>
        </article>

        <ol>
            <?php
            $stmt_topic = $dbconnect->prepare("SELECT topic_id, topic_subject, topic_date FROM topics WHERE topic_display = 1 AND topic_board_id = 5  AND topic_id BETWEEN 0 AND (SELECT topic_id FROM topics WHERE topic_board_id = 5 ORDER BY topic_id DESC LIMIT 1)-1 ORDER BY topic_date DESC LIMIT 5") or die($dbconnect->error);
            $stmt_topic->execute();
            $stmt_topic->bind_result($topic_title, $topic_date, $topic_id);
            $result_topic = $stmt_topic->get_result();
            $stmt_topic->close();
            if(mysqli_num_rows($result_topic) > 0){
                while ($row_topic = $result_topic->fetch_assoc()){
                    $topic_title = $row_topic['topic_subject'];
                    $topic_date = new DateTime($row_topic['topic_date']);
                    $topic_id = $row_topic['topic_id'];

                    $stmt_topic_author = $dbconnect->prepare("SELECT `user-accounts`.`user_name`, `user-profile`.`profile_avatar` FROM (SELECT posts.post_author_id FROM topics INNER JOIN posts ON posts.post_topic_id = ? ORDER BY posts.post_date ASC) AS author INNER JOIN `user-accounts` ON `user-accounts`.`user_id` = author.post_author_id INNER JOIN `user-profile` ON `user-profile`.profile_user_id = author.post_author_id LIMIT 1") or die($dbconnect->error);
                    $stmt_topic_author->bind_param('i', $topic_id);
                    $stmt_topic_author->execute();
                    $stmt_topic_author->bind_result($user_name, $post_author_avi);
                    $result_topic_author = $stmt_topic_author->get_result();
                    $stmt_topic_author->close();
                    if (mysqli_num_rows($result_topic_author) > 0){
                        while ($row_topic_author = $result_topic_author->fetch_assoc()){
                            $user_name = $row_topic_author["user_name"];
                            $post_author_avi = $row_topic_author["profile_avatar"];         
                            
                             echo"<li><a href='https://dashmash.ddns.net/forum/topic.php?id=$topic_id'>"
                                . "<img src='//dashmash.ddns.net/lib/user/uploads/avatar/$post_author_avi' alt='avatar'><em>$topic_title</em><span>Date: <strong>"
                                . $topic_date->format('d-m-Y') . "</strong> Author: <strong>$user_name</strong></span></a></li>";
                        }
                    } 
                }
            }
            ?>
        </ol>
    </div>


    <footer><div><a href="forum/board.php?id=5">Older News</a></div></footer>
</section>