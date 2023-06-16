<?php

include '../lib/commons/connect.php';
include '../lib/commons/session.php';
require_once '../lib/forum_functionality/bbcode_parser.php';

$post_content = BBcode(htmlspecialchars($_POST["content"]));
$topic_subject = $dbconnect->real_escape_string(filter_input(INPUT_POST, "subject"));
$topic_board_id = filter_input(INPUT_GET, "board");

if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST' && $user_log_level != 10) {
    if ($post_content != null && $topic_subject != null) {

        $query_topic = $dbconnect->prepare("INSERT INTO topics (topic_subject, topic_board_id) VALUES (?, ?)");
        $query_topic->bind_param('si', $topic_subject, $topic_board_id);
        $query_topic->execute();
        $query_topic->close();

        $topic_id = $dbconnect->insert_id;
        $query_post = $dbconnect->prepare("INSERT INTO posts (post_topic_id, post_author_id, post_content) VALUES (?, ?, ?)");
        $query_post->bind_param('iis', $topic_id, $user_log_id, $post_content);
        $query_post->execute();
        $query_post->close();

        $dbconnect->close();
        header("Location: https://dashmash.ddns.net/forum/topic.php?id=$topic_id"); /* Redirects to the created topic */
        exit;
    } else {
        echo "Topic entries cannot be empty<br>";
        echo "<a href='https://dashmash.ddns.net/forum/createtopic.php?board=$topic_board_id'>Go back</a>";
    }
} else {
    header("Location: https://dashmash.ddns.net/?action=index"); /* Redirects to home if linked manually */
}