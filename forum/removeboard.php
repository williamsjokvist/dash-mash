<?php

include '../lib/commons/connect.php';
include '../lib/commons/session.php';
$board_id = filter_input(INPUT_GET, "board");

if ($user_log_level == 1) {
    $remove_board = $dbconnect->prepare("UPDATE boards SET board_display = 0 WHERE board_id = ?") or die($dbconnect->error);
    $remove_board->bind_param('i', $board_id);
    $remove_board->execute();
    $remove_board->close();
    $remove_topic = $dbconnect->prepare("UPDATE topics SET topic_display = 0 WHERE topic_board_id = ?") or die($dbconnect->error);
    $remove_topic->bind_param('i', $board_id);
    $remove_topic->execute();
    $remove_topic->close();
}

header("Location: https://dashmash.ddns.net/forum.php"); /* Redirects to the created topic */
exit;

