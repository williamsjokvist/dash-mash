<?php
$stmt_rank_check = $dbconnect->prepare("SELECT * FROM `rankings` WHERE rank_user = ?") or die($dbconnect->error);
$stmt_rank_check->bind_param('i', $user_id);
$stmt_rank_check->execute();
$result_rank_check = $stmt_rank_check->get_result();
$stmt_rank_check->close();

$stmt_rank = $dbconnect->prepare("SELECT COUNT(rank_user)+1 AS rank FROM `rankings` WHERE rank_score > (SELECT rank_score FROM `rankings` WHERE rank_user = ?);") or die($dbconnect->error);
$stmt_rank->bind_param('i', $user_id);
$stmt_rank->execute();
$stmt_rank->bind_result($rank);
$result_rank = $stmt_rank->get_result();
$stmt_rank->close();

if (mysqli_num_rows($result_rank_check) > 0) {
    while ($row_rank = $result_rank->fetch_assoc()) {
        $rank = '#' . $row_rank["rank"];
    }
} else {
    $rank = "unranked";
}
?>
<header>
    <?php include "lib/commons/notification-bar.php"; ?>
    <profile-banner>
        <?php
        if ($user_session) {
            echo "<label for='file-upload'><span>Upload Banner</span></label><input form='edit-profile' type='file' name='banner-upload' class='btn-upload'/>";
        }
        ?>       
        <banner-image class='parallax'></banner-image>
    </profile-banner>
    <profile-bar>
        <div>
            <div>
                <figure class="avatar-container">
                    <?php
                    if ($user_session) {
                        echo "<label for='file-upload'><span>Upload avatar</span></label><input form='edit-profile' type='file' name='avatar-upload' class='btn-upload'/>";
                    }
                    ?>

                    <?php
                    /* //////////////////////////////// FETCH AVATAR ///////////////////////// */

                    $stmt_avi = $dbconnect->prepare("SELECT profile_avatar FROM `user-profile` WHERE profile_user_id = ?") or die($dbconnect->error);
                    $stmt_avi->bind_param('s', $user_id);
                    $stmt_avi->execute();
                    $result_avi = $stmt_avi->get_result();

                    if (mysqli_num_rows($result_avi) > 0) {
                        while ($row_avi = $result_avi->fetch_assoc()) {
                            $profile_avi = $row_avi["profile_avatar"];

                            if ($profile_avi == null) {
                                echo "<img src='//dashmash.ddns.net/lib/user/uploads/avatar/default.png' alt='avatar'/>";
                            } else {
                                echo "<img src='//dashmash.ddns.net/lib/user/uploads/avatar/$profile_avi' alt='avatar'/>";
                            }
                        }
                    } else {
                        echo "<img src='//dashmash.ddns.net/lib/user/uploads/avatar/default.png' alt='avatar'/>";
                    }

                    $stmt_avi->close();
                    ?>
                </figure>
                <?php
                echo "<a href='//dashmash.ddns.net/profile.php?id=$user_name'>$user_name</a>";
                if ($user_level == 10) {
                    echo "<strong>BANNED</strong>";
                }

                if ($user_online == 1) {
                    echo "<span style='color:#10e910;  margin-left: 10px;'>Online</span>";
                } else if ($user_online == 0) {
                    echo "<span style='color:#ec2a24;  margin-left: 10px;'>Offline</span>";
                }
                ?>
                <div>
                    <?php
                    if ($user_session) {
                        echo "<button id='save' form='edit-profile' type='submit'>Save Changes?</button>";
                        echo "<button id='edit'>Edit Profile</button>";
                    } else if (isset($_SESSION["logged_in"]) && $user_log_id && $user_log_level != 10) {
                        echo "<a href='javascript:void(0)' type='toggler' class='fat'>PM</a>
                            <form data-toggled action='lib/user/sendpm.php?user=$user_id' method='POST' class='subsection pm'>
                                <header><h4>PM $user_name</h4><a class='exit' href='javascript:void(0)'></a></header>
                                <fieldset>
                                    <textarea name='content' placeholder='Your Message'></textarea><button type='submit'>Send</button>
                                </fieldset>
                            </form>";

                        if (isset($friend_status)) {
                            if ($friend_status == 2) {
                                echo "<a href='//dashmash.ddns.net/lib/user/request.php?user=$user_id&name=$user_name' class='fat'>Friend Request</a>";
                            } else if ($friend_status == 1) {
                                echo "<a href='//dashmash.ddns.net/lib/user/removefriend.php?user=$user_id&name=$user_name' class='fat' style='color:#ff3c3c'>Remove Friend</a>";
                            } 
                        } else {
                            echo "<a href='//dashmash.ddns.net/lib/user/request.php?user=$user_id&name=$user_name' class='fat'>Friend Request</a>";
                        }

                        if ($user_log_level != $admin) {
                            echo "
                        <a href='javascript:void(0)' type='toggler' class='fat'>Report</a>
                            <form data-toggled action='lib/user/report.php?user=$user_id' method='POST' class='subsection'>
                                <header><h4>Report $user_name</h4><a class='exit' href='javascript:void(0)'></a></header>
                                <fieldset>
                                    <textarea name='content' placeholder='Report Reason'></textarea><button type='submit'>Report</button>
                                </fieldset>
                            </form>";
                        } else {
                            /* Admin */
                            echo "<a href='javascript:void(0)' type='toggler' class='fat'>Ban</a>
                            <form data-toggled action='lib/user/ban.php?user=$user_id' method='POST' class='subsection ban'>
                                <header><h4>Ban $user_name</h4><a class='exit' href='javascript:void(0)'></a></header>
                                <fieldset>
                                    <textarea name='content' placeholder='Ban Reason'></textarea><button type='submit'>Ban</button>
                                </fieldset>
                            </form>";

                            if ($user_level == 10) {
                                /* Profile banned */
                                echo "                        <a href='javascript:void(0)' type='toggler' class='fat'>Unban</a>
                            <form data-toggled action='lib/user/unban.php?user=$user_id' method='POST' class='subsection ban'>
                                <header><h4>Unban $user_name</h4><a class='exit' href='javascript:void(0)'></a></header>
                                <fieldset>
                                    <textarea name='content' placeholder='Unban Reason'></textarea><button type='submit'>Unban</button>
                                </fieldset>
                            </form>";
                            }
                        }
                    }
                    ?>
                </div>
            </div>
            <div><button type="button" id="info-toggler"></button></div>
        </div>
        <user-info>
            <ul>
                <li><b>Ranking:</b><?php echo $rank; ?></li>
                <li><b>Joined: </b> <?php echo "<time>" . $user_joindate->format('d, M Y') . "</time>"; ?></li>
                <li><b>Forum Posts: </b> 
                    <?php
                    $stmt_user_post_count = $dbconnect->prepare("SELECT post_id FROM `posts` WHERE post_author_id = ?");
                    $stmt_user_post_count->bind_param('s', $user_id);
                    $stmt_user_post_count->execute();
                    $result_user_post_count = $stmt_user_post_count->get_result();
                    $stmt_user_post_count->close();

                    echo mysqli_num_rows($result_user_post_count);
                    ?></li>
            </ul>

            <?php
            /* SOCIAL LINKS */

            $stmt_profile_social = $dbconnect->prepare("SELECT profile_discord, profile_twitter, profile_youtube, profile_twitch, profile_steam FROM `user-profile` WHERE profile_user_id = ?");
            $stmt_profile_social->bind_param('i', $user_id);
            $stmt_profile_social->execute();
            $result_profile_social = $stmt_profile_social->get_result();

            if (mysqli_num_rows($result_profile_social) > 0) {
                while ($row_social = $result_profile_social->fetch_assoc()) {
                    $discord = $row_social["profile_discord"];
                    $twitter = $row_social["profile_twitter"];
                    $youtube = $row_social["profile_youtube"];
                    $twitch = $row_social["profile_twitch"];
                    $steam = $row_social["profile_steam"];
                }
            } else {
                $discord = null;
                $twitter = null;
                $youtube = null;
                $twitch = null;
                $steam = null;
            }

            echo "<ul class = 'user-social'>";

            if ($user_session) {
                echo "<li class = 'user-discord'><span>$discord</span><input form = 'edit-profile' type = 'text' name = 'discord' placeholder = 'Discord ID' maxlength = '32'/></li>";
                echo "<li class = 'user-twitter'><a href = '//twitter.com/$twitter' target = _blank>$twitter</a> <input form = 'edit-profile' type = 'text' name = 'twitter' placeholder = 'Twitter Handle' maxlength = '15'/></li>";
                echo "<li class = 'user-youtube'><a href = '//youtube.com/$youtube' target = _blank>$youtube</a><input form = 'edit-profile' type = 'text' name = 'youtube' placeholder = 'YouTube Channel' maxlength = '20'/></li>";
                echo "<li class = 'user-twitch'><a href = '//twitch.tv/$twitch' target = _blank>$twitch</a><input form = 'edit-profile' type = 'text' name = 'twitch' placeholder = 'Twitch ID' maxlength = '20'/></li>";
                echo "<li class = 'user-steam'><a href = '//steamcommunity.com/id/$steam' target = _blank>$steam</a><input form = 'edit-profile' type = 'text' name = 'steam' placeholder = 'Steam ID' maxlength = '33'/></li>";
            } else {
                if ($discord != null) {
                    echo "<li class = 'user-discord'><span>$discord</span></li>";
                }
                if ($twitter != null) {
                    echo "<li class = 'user-twitter'><a href = '//twitter.com/$twitter' target = _blank>$twitter</a></li>";
                }
                if ($youtube != null) {
                    echo "<li class = 'user-youtube'><a href = '//youtube.com/$youtube' target = _blank>$youtube</a></li>";
                }
                if ($twitch != null) {
                    echo "<li class = 'user-twitch'><a href = '//twitch.tv/$twitch' target = _blank>$twitch</a></li>";
                }
                if ($steam != null) {
                    echo "<li class = 'user-steam'><a href = '//steamcommunity.com/id/$steam' target = _blank>$steam</a></li>";
                }
            }
            echo "</ul>";
            ?>
        </user-info>
    </profile-bar>
</header>
<?php include 'lib/commons/nav.php'; ?>
<main>
    <section>
        <div class="profile-container">
            <?php
            if ($user_session) {
                echo "<div id='bg-btn-container'><label for='file-upload'><span>Upload Background</span></label><input form='edit-profile' type='file' name='bg-upload' class='btn-upload'/></div>";
            }
            ?>
            <section class="user-content">  
                <?php
                /* Fetch user content */
                $stmt_fetch_content = $dbconnect->prepare("SELECT profile_content, profile_editor_content, profile_updated, profile_link_color, profile_bg_color, profile_icon_color, profile_bar_color, profile_part_color FROM `user-profile` WHERE profile_user_id = ?");
                $stmt_fetch_content->bind_param('i', $user_id);
                $stmt_fetch_content->execute();
                $result_fetch_content = $stmt_fetch_content->get_result();
                if (mysqli_num_rows($result_fetch_content) > 0) {
                    while ($row_fetch_content = $result_fetch_content->fetch_assoc()) {
                        $last_updated = $row_fetch_content['profile_updated'];
                        $editor_content = $row_fetch_content["profile_editor_content"];
                        $profile_icon_color = $row_fetch_content["profile_icon_color"];
                        $profile_bg_color = $row_fetch_content["profile_bg_color"];
                        $profile_link_color = $row_fetch_content["profile_link_color"];
                        $profile_bar_color = $row_fetch_content["profile_bar_color"];
                        $profile_part_color = $row_fetch_content["profile_part_color"];
                        $profile_content = $row_fetch_content['profile_content'];
                    }
                } else {
                    $profile_content = null;
                }
                $stmt_fetch_content->free_result();
                $stmt_fetch_content->close();
                ?>

                <?php
                /* Add profile editor if profile belongs to logged in user */
                if ($user_session) {
                    echo "<div class='editor'><header><h3>Editor</h3></header>";
                    if ($profile_content == null) {
                        echo "<textarea form='edit-profile' name='content' maxlength='2000' placeholder='Write your BBCode here'></textarea>";
                    } else if ($profile_content != null) {
                        echo "<textarea form='edit-profile' name='content' maxlength='2000' placeholder='$editor_content (Last updated: " . $last_updated . ")'>$editor_content</textarea>";
                    }
                    echo "<dl><dt>Supported BBCode</dt><dd> [b] <b>bold</b> [/b] </dd><dd>[i] <i>italic</i> [/i]</dd><dd>[u] <u>underlined</u> [/u]</dd><dd>[quote] <pre>quote</pre> [/quote]</dd><dd>[size=*]<span style='font-size:15px;'> text </span>[/size] <small>(defined in relatives eg. '12')</small></dd><dd>[color=*]<span style='color:lightgreen;'> color </span>[/color] <small>(HEX color codes)</small></dd><dd>[url]<a> link </a>[/url]</dd><dd>[video] url [/video] <small>(webm | mp4)</small></dd><dd>[audio] url [/audio] <small>(mp3 | wav | ogg)</small></dd><dd>[img] url [/img] <small>(jpg | gif |png | bmp)</small></dd><dd>[center] * [/center]</dd><dd>[yt]*[/yt] <small>(YouTube Video ID)</small></dd></dl>"
                    . "<h3>Color Picker</h3>"
                    . "<div><label for='icon-color'>Icons</label><div><input name='icon-color' value='0' form='edit-profile' type='radio'/><input name='icon-color' value='1' form='edit-profile' type='radio'/><input name='icon-color' value='2' form='edit-profile' type='radio'/><input name='icon-color' value='3' form='edit-profile' type='radio'/><input name='icon-color' value='4' form='edit-profile' type='radio'/><input name='icon-color' value='5' form='edit-profile' type='radio'/></div></div>"
                    . "<div><label for='link-color'>Links</label><div><input name='link-color' value='0' form='edit-profile' type='radio'/><input name='link-color' value='1' form='edit-profile' type='radio'/><input name='link-color' value='2' form='edit-profile' type='radio'/><input name='link-color' value='3' form='edit-profile' type='radio'/><input name='link-color' value='4' form='edit-profile' type='radio'/><input name='link-color' value='5' form='edit-profile' type='radio'/></div></div>"
                    . "<div><label for='bg-color'>Background</label><div><input name='bg-color' value='0' form='edit-profile' type='radio'/><input name='bg-color' value='1' form='edit-profile' type='radio'/><input name='bg-color' value='2' form='edit-profile' type='radio'/><input name='bg-color' value='3' form='edit-profile' type='radio'/><input name='bg-color' value='4' form='edit-profile' type='radio'/><input name='bg-color' value='5' form='edit-profile' type='radio'/><input name='bg-color' value='6' form='edit-profile' type='radio'/></div></div>"
                    . "<div><label for='part-color'>Sections</label><div><input name='part-color' value='0' form='edit-profile' type='radio'/><input name='part-color' value='1' form='edit-profile' type='radio'/><input name='part-color' value='2' form='edit-profile' type='radio'/><input name='part-color' value='3' form='edit-profile' type='radio'/><input name='part-color' value='4' form='edit-profile' type='radio'/><input name='part-color' value='5' form='edit-profile' type='radio'/></div></div>";
                }
                ?>
                <script defer>
                    document.addEventListener("DOMContentLoaded", function () {
                        function check(val, name) {
                            let input = document.querySelector('input[value="' + val + '"][name="' + name + '"]');
                            if (input) {
                                input.checked = true;
                            }
                        }

                        check(<?php echo $profile_bg_color ?>, "bg-color");
                        check(<?php echo $profile_link_color ?>, "link-color");
                        check(<?php echo $profile_icon_color ?>, "icon-color");
                        check(<?php echo $profile_part_color ?>, "part-color");
                    });
                </script>
                <?php
                if ($user_session) {
                    echo "</div>";
                }
                echo "<div class='content-showcase'>$profile_content</div>";
                ?>


            </section>
            <section class="profile-friends">
                <header><h3>Friends</h3></header>
                <?php
                /* fetch friends */
                $fetch_profile_friends = $dbconnect->prepare("SELECT * FROM ((SELECT friends.friend_date_status, `user-accounts`.`user_name`, `user-accounts`.`user_online`, `user-profile`.`profile_avatar` FROM friends "
                        . "INNER JOIN `user-accounts` ON `user-accounts`.`user_id` = friends.requester_id "
                        . "INNER JOIN `user-profile` ON `user-profile`.`profile_user_id` = friends.requester_id "
                        . "WHERE friends.requested_id = ? AND friends.friend_status = 1 LIMIT 30) "
                        . "UNION "
                        . "(SELECT friends.friend_date_status, `user-accounts`.`user_name`, `user-accounts`.`user_online`, `user-profile`.`profile_avatar` FROM friends "
                        . "INNER JOIN `user-accounts` ON `user-accounts`.`user_id` = friends.requested_id "
                        . "INNER JOIN `user-profile` ON `user-profile`.`profile_user_id` = friends.requested_id "
                        . "WHERE friends.requester_id = ? AND friends.friend_status = 1 LIMIT 30)) as friendlist ORDER BY friendlist.`user_online`DESC") or die($dbconnect->error);
                $fetch_profile_friends->bind_param('ii', $user_id, $user_id);
                $fetch_profile_friends->execute();
                $result_profile_friends = $fetch_profile_friends->get_result();
                if (mysqli_num_rows($result_profile_friends) > 0) {
                    echo "<ul>";
                    while ($row_profile_friends = $result_profile_friends->fetch_assoc()) {
                        $friend_name = $row_profile_friends['user_name'];
                        $friend_online = $row_profile_friends['user_online'];
                        $friend_avatar = $row_profile_friends['profile_avatar'];

                        echo "<li><a href='//dashmash.ddns.net/profile.php?id=$friend_name'><img src='//dashmash.ddns.net/lib/user/uploads/avatar/$friend_avatar'/>";
                        echo "<div class='user-online' style='background-color:";
                        if ($friend_online == 1) {
                            echo "#10e910";
                        } else if ($friend_online == 0) {
                            echo "#ec2a24";
                        }
                        echo "'></div><span>$friend_name</span></a></li>";
                    }
                    echo "</ul>";
                }
                ?>
            </section> 
            <?php
            /* Latest Forum Activity */
            $stmt_user_post = $dbconnect->prepare("SELECT post_date, post_topic_id, topics.topic_subject FROM `posts` "
                    . "INNER JOIN topics ON topics.topic_id = posts.post_topic_id "
                    . "WHERE post_author_id  = ? AND post_display = 1 ORDER BY post_id DESC LIMIT 5");
            $stmt_user_post->bind_param('i', $user_id);
            $stmt_user_post->execute();
            $result_user_post = $stmt_user_post->get_result();
            if (mysqli_num_rows($result_user_post) > 0) {
                echo "<section class='profile-forum'><header><h3>Latest Forum Activity</h3></header><ol>";
                while ($row_user_post = $result_user_post->fetch_assoc()) {
                    $post_date = time_elapsed_string($row_user_post["post_date"], false);
                    $post_topic_id = $row_user_post["post_topic_id"];
                    $topic_subject = $row_user_post["topic_subject"];
                    echo "<li><a href='//dashmash.ddns.net/forum/topic.php?id=$post_topic_id'>$topic_subject <span>Posted: <time>$post_date</time></span></a></li>";
                }
                echo "</ol></section>";
            }

            $stmt_user_post->close();
            ?>


            <section class="comments">
                <header><h3>Comments</h3></header>
                <?php
                $stmt_comments = $dbconnect->prepare("SELECT `profile-comments`.com_id, `profile-comments`.com_author_id, `profile-comments`.com_content, `profile-comments`.com_date, `user-accounts`.user_name, `user-accounts`.user_online, `user-profile`.profile_avatar FROM `profile-comments` INNER JOIN `user-accounts` ON `profile-comments`.com_author_id = `user-accounts`.user_id INNER JOIN `user-profile` ON `profile-comments`.com_author_id = `user-profile`.profile_user_id WHERE com_profile_id = ? AND com_display = 1") or die($dbconnect->error);
                $stmt_comments->bind_param('i', $user_id);
                $stmt_comments->execute();
                $result_comments = $stmt_comments->get_result();
                if (mysqli_num_rows($result_comments) > 0) {
                    echo "<ol>";
                    while ($row_comments = $result_comments->fetch_assoc()) {
                        $comment_id = $row_comments["com_id"];
                        $comment = $row_comments["com_content"];
                        $comment_author_id = $row_comments["com_author_id"];
                        $comment_date = time_elapsed_string($row_comments["com_date"], false);
                        $comment_author = $row_comments["user_name"];
                        $comment_author_online = $row_comments["user_online"];
                        $comment_author_avi = $row_comments['profile_avatar'];

                        echo "<li><article><header><a href='//dashmash.ddns.net/profile.php?id=$comment_author'><img src='https://dashmash.ddns.net/lib/user/uploads/avatar/$comment_author_avi' alt='avatar'/><div class='user-online' style='background-color:";
                        if ($comment_author_online == 1) {
                            echo "#10e910";
                        } else if ($comment_author_online == 0) {
                            echo "#ec2a24";
                        }
                        echo "'></div><div><span>$comment_author</span><time>$comment_date</time></div></a></header><p>$comment</p>";

                        if ($user_session) {
                            /* Show delete btn if user sesh */
                            echo "<div><a href='//dashmash.ddns.net/lib/user/removecom.php?id=$comment_id&uid=$user_id' class='fat'>Delete</a></div>";
                        }
                        echo "</article></li>";
                    }
                    echo "</ol>";
                }

                $stmt_comments->close();
                ?>

                <?php
                if (isset($_SESSION["logged_in"]) && $user_log_id && $user_log_level != 10) {
                    echo "<form action='lib/user/postcom.php?id=$user_id&auth=$user_log_id' id='topic-reply' method='POST'>";

                    echo "<div><span>You are logged in as: <a href='//dashmash.ddns.net/profile.php?id=$user_log_name'>$user_log_name</a></span><a href='javascript:void(0)' type='toggler'>BBCode List</a><dl data-toggled><dt>Supported BBCode</dt><dd> [b] <b>bold</b> [/b] </dd><dd>[i] <i>italic</i> [/i]</dd><dd>[u] <u>underlined</u> [/u]</dd><dd>[quote] <pre>quote</pre> [/quote]</dd><dd>[size=*]<span style='font-size:15px;'> text </span>[/size] <small>(defined in pixels eg. 12px)</small></dd><dd>[color=*]<span style='color:lightgreen;'> color </span>[/color] <small>(HEX color codes)</small></dd><dd>[url]<a> link </a>[/url]</dd><dd>[video] url [/video] <small>(webm | mp4)</small></dd><dd>[audio] url [/audio] <small>(mp3 | wav | ogg)</small></dd><dd>[img] url [/img] <small>(jpg | gif |png | bmp)</small></dd><dd>[yt]*[/yt] <small>(YouTube Video ID)</small></dd></dl></div>";

                    echo "<fieldset><legend>Post Comment</legend><textarea rows = '10' cols = '100' maxlength = '10000' name = 'content' placeholder = 'Write a new comment'></textarea><div><span></span><button type = 'submit'>Post</button></div></fieldset></form>";
                }
                ?>

            </section>
        </div>
    </section>
    <?php include 'lib/commons/page-footing.php'; ?>
</main>