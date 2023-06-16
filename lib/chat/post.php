<?php

include '../commons/connect.php';
include '../commons/session.php';
require_once '../forum_functionality/bbcode_parser.php';

$post_content = BBcode(htmlspecialchars(filter_input(INPUT_GET, 'content')));

if ($user_log_id && $user_log_level != 10 && $post_content != null) {
    $query_post = $dbconnect->prepare("INSERT INTO chat (chat_user_id, chat_content) VALUES (?, ?)");
    $query_post->bind_param('is', $user_log_id, $post_content);
    $query_post->execute();
    $query_post->close();
}

$dbconnect->close();