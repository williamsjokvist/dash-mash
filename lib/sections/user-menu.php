<?php

/* FETCH AVATAR / User Menu */
$stmt_avi = $dbconnect->prepare("SELECT profile_avatar FROM `user-profile` WHERE profile_user_id = ?");
$stmt_avi->bind_param('s', $user_log_id);
$stmt_avi->execute();
$result_avi = $stmt_avi->get_result();
$stmt_avi->close();
if (mysqli_num_rows($result_avi) > 0) {
    while ($row_avi = $result_avi->fetch_assoc()) {
        $post_author_avi = $row_avi["profile_avatar"];
        echo "<a href='javascript:void(0)' type='toggler' class='user-link' style='background-image:url(//dashmash.ddns.net/lib/user/uploads/avatar/$post_author_avi);' title='Open user menu'>$user_log_name</a>";
    }
}

echo "<section class='user-menu' data-toggled><header><h4>User menu</h4></header><ul>"
 . "<li><a href='//dashmash.ddns.net/profile.php?id=$user_log_name' title='Go to Your Profile'>Profile</a></li>"
 . "<li><a href='javascript:void(0)' type='toggler'>Friends</a>";

if (preg_match('/board.php/', $page) || preg_match('/topic.php/', $page)) {
    include "../lib/sections/friends.php";
} else {
    include "lib/sections/friends.php";
}

echo "</li><li><a href='javascript:void(0)' type='toggler'>Settings</a>";

if (preg_match('/board.php/', $page) || preg_match('/topic.php/', $page)) {
    include "../lib/sections/settings.php";
} else {
    include "lib/sections/settings.php";
}

echo "</li><li><a href = '//dashmash.ddns.net/lib/user/logout.php?s=$user_log_id' title='Sign Out'>Sign Out</a></li></ul></section>";