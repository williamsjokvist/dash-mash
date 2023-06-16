<?php

include '../commons/connect.php';
include '../commons/session.php';
$user_id = filter_input(INPUT_GET, "user", FILTER_SANITIZE_NUMBER_INT);

if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST' && $user_log_level == $admin) {
    $user_level_set = $banned;
    $report_status_set = 1;

    $user_level = $dbconnect->prepare("UPDATE `user-accounts` SET user_level = ? WHERE user_id = ?") or die($dbconnect->error);
    $user_level->bind_param('ii', $user_level_set, $user_id);
    $user_level->execute();
    $user_level->close();

    $report_status = $dbconnect->prepare("UPDATE `user-reports` SET report_status = ? WHERE reported_user_id = ?") or die($dbconnect->error);
    $report_status->bind_param('ii', $report_status_set, $user_id);
    $report_status->execute();
    $report_status->close();

    header("Location: https://dashmash.ddns.net/"); 
    exit;
}