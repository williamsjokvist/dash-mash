<?php

include '../commons/connect.php';
include '../commons/session.php';

$uid = filter_input(INPUT_GET, "uid", FILTER_SANITIZE_NUMBER_INT);

if ($user_log_id == $uid) {

    $com = filter_input(INPUT_GET, "id",FILTER_SANITIZE_NUMBER_INT);
    $display = 0;
    $rmv_com = $dbconnect->prepare("UPDATE `profile-comments` SET com_display = ? WHERE com_id = ?") or die($dbconnect->error);
    $rmv_com->bind_param('ii', $display, $com);
    $rmv_com->execute();
    $rmv_com->close();
    
    header("Location: https://dashmash.ddns.net/profile.php?id=$user_log_name"); /* Redirects back to profile */
    exit;
} else {
    header("Location: https://dashmash.ddns.net/?action=error");
    exit;
}