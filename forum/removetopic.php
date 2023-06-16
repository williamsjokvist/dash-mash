<?php

include '../lib/commons/connect.php';
include '../lib/commons/session.php';
$topic_id = filter_input(INPUT_GET, "topic", FILTER_SANITIZE_NUMBER_INT);
$topic_board_id = filter_input(INPUT_GET, "board", FILTER_SANITIZE_NUMBER_INT);

if ($user_log_id && $user_log_level == $admin) {
    $display = 0;
    $remove_topic = $dbconnect->prepare("UPDATE topics SET topic_display = ? WHERE topic_id = ?") or die($dbconnect->error);
    $remove_topic->bind_param('ii', $display, $topic_id);
    $remove_topic->execute();
    $remove_topic->close();
}

header("Location: https://dashmash.ddns.net/forum/board.php?id=$topic_board_id"); /* Redirects back to the board */
exit;
