<?php

session_start();
$user = 0;
$admin = 1;
$banned = 10;
$page = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);

if (!isset($_SESSION["logged_in"]) || empty($_SESSION["logged_in"]) || $_SESSION["logged_in"] == false) {
    $user_log_level = null;
    $user_log_id = null;
    $_SESSION["logged_in"] = false;
} else {

    /* FETCH GLOBAL USER INFO */
    $stmt_user_log = $dbconnect->prepare("SELECT user_id, user_level, user_name, user_joindate FROM `user-accounts` WHERE user_id = ?") or die($dbconnect->error);
    $stmt_user_log->bind_param('i', $_SESSION["logged_in"]);
    $stmt_user_log->execute();

    $stmt_user_log->bind_result($user_log_id, $user_id, $user_log_name, $user_log_joindate);
    $result_user_log = $stmt_user_log->get_result();

    if (mysqli_num_rows($result_user_log) > 0) {
        while ($row_user_log = $result_user_log->fetch_assoc()) {
            $user_log_id = $row_user_log["user_id"];
            $user_log_level = $row_user_log["user_level"];
            $user_log_name = $row_user_log["user_name"];
            $user_log_joindate = $row_user_log["user_joindate"];

            $_SESSION["user_level"] = $user_log_level;
        }
    } else {
        $user_log_level = null;
        $user_log_id = null;
    }
    $stmt_user_log->close();

    /* UPDATE USER ONLINE STATE */
    $stmt_set_online = $dbconnect->prepare("UPDATE `user-accounts` SET user_online = 1, user_logged = NOW() WHERE user_id = ?");
    $stmt_set_online->bind_param('s', $user_log_id);
    $stmt_set_online->execute();
    $stmt_set_online->close();
}

/* SET USER OFFLINE STATE AFTER 5 MIN*/
$stmt_set_offline = $dbconnect->prepare("UPDATE `user-accounts` SET user_online = 0 WHERE user_logged < NOW() - INTERVAL 5 MINUTE");
$stmt_set_offline->execute();
$stmt_set_offline->close();
