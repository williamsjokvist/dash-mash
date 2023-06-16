<?php
include '../commons/connect.php';
include '../commons/session.php';
$user_id = filter_input(INPUT_GET, "user");
$user_name = filter_input(INPUT_GET, "name");

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_SESSION["logged_in"]) && $user_log_level != 10){
$stmt_check = $dbconnect->prepare("(SELECT * FROM friends WHERE requested_id = ? AND requester_id = ?) UNION (SELECT * FROM friends WHERE requested_id = ? AND requester_id = ?)");
$stmt_check->bind_param('iiii', $user_id, $user_log_id, $user_log_id, $user_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

    if(mysqli_num_rows($result_check) > 0){
        
        $stmt_request = $dbconnect->prepare("UPDATE friends SET friend_status = 0, requester_id = ? , requested_id = ?  WHERE (requested_id = ? AND requester_id = ?) OR (requested_id = ? AND requester_id = ?)");
        $stmt_request->bind_param('iiiiii', $user_log_id, $user_id, $user_id, $user_log_id, $user_log_id, $user_id);
        $stmt_request->execute();
        $stmt_request->close();   
    }else {
    
        $stmt_request = $dbconnect->prepare("INSERT INTO friends (friend_status, requester_id, requested_id) VALUES (0, ?, ?)");
        $stmt_request->bind_param('ii', $user_log_id, $user_id);
        $stmt_request->execute();
        $stmt_request->close();
    }   
}

header("Location: https://dashmash.ddns.net/profile.php?id=$user_name"); /* Redirects back to profile */
