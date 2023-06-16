<?php
include '../commons/connect.php';
include '../commons/session.php';

if (isset($_SESSION["logged_in"]) && $user_log_id) {
    $fr = $dbconnect->prepare("UPDATE `friends` SET friend_status = ?, friend_date_status = NOW() WHERE request_id = ? AND requested_id = ?") or die($dbconnect->error);
    $fr->bind_param('iii', filter_input(INPUT_GET, "accept"), filter_input(INPUT_GET, "id"), $user_log_id);
    $fr->execute();
    $fr->close();

    header("Location: https://dashmash.ddns.net/"); 
    exit;
}