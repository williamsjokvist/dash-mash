<?php

include '../lib/commons/connect.php';
include '../lib/commons/session.php';

$board_title = $dbconnect->real_escape_string(filter_input(INPUT_POST, "boardname"));
$board_level = $dbconnect->real_escape_string(filter_input(INPUT_POST, "boardlevel"));

if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST' && $user_log_level == 1) {
    if ($board_title != null && $board_level != null) {

        $query_topic = $dbconnect->prepare("INSERT INTO boards (board_level, board_title) VALUES (?, ?)");
        $query_topic->bind_param('is', $board_level, $board_title);
        $query_topic->execute();
        $query_topic->close();
        $dbconnect->close();
        header("Location: https://dashmash.ddns.net/forum.php");
        exit;
    } else {
        echo "Board entries cannot be empty<br>";
        echo "<a href='https://dashmash.ddns.net/forum/createboard.php'>Go back</a>";
    }
} else {
   header("Location: https://dashmash.ddns.net/?action=index"); exit;  /*Redirects to home if linked manually */
}