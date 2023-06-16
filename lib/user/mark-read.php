<?php
include '../commons/connect.php';
include '../commons/session.php';

if (isset($_SESSION["logged_in"]) && $user_log_id) {
    $message_status = $dbconnect->prepare("UPDATE `personal-message` SET pm_read = 1 WHERE message_id = ? AND message_reciver_id = ?") or die($dbconnect->error);
    $message_status->bind_param('ii', filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT), $user_log_id);
    $message_status->execute();
    $message_status->close();

    header("Location: https://dashmash.ddns.net/"); 
    exit;
}