<?php

include "../commons/connect.php";

if (filter_input(INPUT_SERVER, "REQUEST_METHOD") == "POST") {

    $user_pass = $dbconnect->escape_string(filter_input(INPUT_POST, "password"));
    $user_pass = sha1($user_pass);
    $user_name = $dbconnect->escape_string(filter_input(INPUT_POST, "username"));

    $stmt_user_check = $dbconnect->prepare("SELECT user_id FROM `user-accounts` WHERE user_name = ? AND user_pass = ?");
    $stmt_user_check->bind_param('ss', $user_name, $user_pass);
    $stmt_user_check->execute();
    $stmt_user_check->bind_result($user_id);
    $stmt_user_check->fetch();
    $stmt_user_check->close();

    $dbconnect->close();

    if ($user_id == null || empty($user_id)) {
        $_SESSION['acc_msg'] = "Your login information is wrong";
    } else {
        session_start();
        $_SESSION['logged_in'] = $user_id;
        $_SESSION['acc_msg'] = "Welcome" . " " . $user_name . "!";
    }
}

$game_check = filter_input(INPUT_GET, "game");

if ($game_check == "true") {
    header("Location: //dashmash.ddns.net/game.php"); /* Redirects to the game */
} else {
    header("Location: //dashmash.ddns.net/?action=index"); /* Redirects to the home page */
    exit;
}
    