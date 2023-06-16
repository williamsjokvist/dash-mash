<?php
include '../commons/connect.php';
include '../commons/session.php';
$message_sender_ip = filter_input(INPUT_SERVER, "REMOTE_ADDR", FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
$pm_receiver_id = filter_input(INPUT_GET, "user", FILTER_SANITIZE_NUMBER_INT);
$message = filter_input(INPUT_POST, "content", FILTER_SANITIZE_STRING);

if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST' && $user_log_id && $user_log_level != 10) {
    if ($message != null) {
        $query_pm = $dbconnect->prepare("INSERT INTO `personal-message` (message_reciver_id, message_sender_id, message, message_sender_ip) VALUES (?, ?, ?, ?)");
        $query_pm->bind_param('iiss', $pm_receiver_id, $user_log_id, $message, $message_sender_ip);
        $query_pm->execute();

        $query_username = $dbconnect->prepare("SELECT user_name FROM `user-accounts` WHERE user_id = ?");
        $query_username->bind_param('i', $pm_receiver_id);
        $query_username->execute();

        $result_username = $query_username->get_result();
        while ($row_username = $result_username->fetch_assoc()) {
            $username = $row_username["user_name"];
        }

        $query_pm->close();
        $query_username->close();
        header("Location: https://dashmash.ddns.net/profile.php?id=$username");
        exit;
    } else {
        echo "Messages cannot be empty<br>";
        echo "<a href='https://dashmash.ddns.net/'>Go back</a>";
    }
}
