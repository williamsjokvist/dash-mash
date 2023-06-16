<?php

include '../commons/connect.php';
include '../commons/session.php';
$user_id = filter_input(INPUT_GET, 'user', FILTER_SANITIZE_NUMBER_INT);

if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST' && $user_log_level == $admin) {
    $user_level = $dbconnect->prepare("UPDATE `user-accounts` SET user_level = 0 WHERE user_id = ?") or die($dbconnect->error);
    $user_level->bind_param('i', $user_id);
    $user_level->execute();
    $user_level->close();

    header("Location: https://dashmash.ddns.net/");
    exit;
}