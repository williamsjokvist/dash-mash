<?php

include '../lib/commons/connect.php';
include '../lib/commons/session.php';

if ($user_log_id && $user_log_level == $admin) {
    $post_id = filter_input(INPUT_GET, "post", FILTER_SANITIZE_NUMBER_INT);
    $topic_id = filter_input(INPUT_GET, "topic", FILTER_SANITIZE_NUMBER_INT);

    $display = 0;
    $remove_post = $dbconnect->prepare("UPDATE posts SET post_display = ? WHERE post_id = ?") or die($dbconnect->error);
    $remove_post->bind_param('ii', $display, $post_id);
    $remove_post->execute();
    $remove_post->close();
}

header("Location: https://dashmash.ddns.net/forum/topic.php?id=$topic_id"); /* Redirects to the created topic */
exit;
