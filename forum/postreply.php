<?php

include '../lib/commons/connect.php';
include '../lib/commons/session.php';
require_once '../lib/forum_functionality/bbcode_parser.php';

if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST' && $user_log_level != $banned) {

    $content = $dbconnect->real_escape_string(BBcode(htmlspecialchars($_POST["content"])));

    $topic_id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
    $post_ip = filter_input(INPUT_SERVER, "REMOTE_ADDR");

    if ($content != null) {
        $query = $dbconnect->prepare("INSERT INTO posts (post_content, post_topic_id, post_author_id, post_ip) VALUES (?, ?, ?, ?)") or die($dbconnect->error);
        $query->bind_param('siis', $content, $topic_id, $user_log_id, $post_ip);
        $query->execute();

        $query->close();
        $dbconnect->close();
        header("Location: //dashmash.ddns.net/forum/topic.php?id=$topic_id#topic-reply"); /* Redirects to the created topic */
        exit;
    } else {
        echo "Replies cannot be empty<br>";
        echo "<a href='//dashmash.ddns.net/forum/topic.php?id=$topic_id#topic-reply'>Go back</a>";
    }
} else {
    header("Location: https://dashmash.ddns.net/?action=index"); /* Redirects to home if linked manually */
}