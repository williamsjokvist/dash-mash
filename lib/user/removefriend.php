<?php
include '../commons/connect.php';
include '../commons/session.php';
$user_id = filter_input(INPUT_GET, "user", FILTER_SANITIZE_NUMBER_INT);
$user_name = filter_input(INPUT_GET, "name", FILTER_SANITIZE_STRING);

if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'GET' && isset($_SESSION["logged_in"]) && $user_log_level != 10){
    $stmt_request = $dbconnect->prepare("UPDATE friends SET friend_status = 2 WHERE (requester_id = ? AND requested_id = ?) OR (requester_id = ? AND requested_id = ?)");
    $stmt_request->bind_param('iiii', $user_log_id, $user_id, $user_id, $user_log_id);
    $stmt_request->execute();
    $stmt_request->close();
}

header("Location: https://dashmash.ddns.net/profile.php?id=$user_name");

