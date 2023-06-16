<?php

include '../commons/connect.php';
include '../commons/session.php';
require_once '../forum_functionality/bbcode_parser.php';

$com_content = BBcode(htmlspecialchars($_POST["content"]));
$com_profile_id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
$com_author_id = filter_input(INPUT_GET, "auth", FILTER_SANITIZE_NUMBER_INT);
$com_ip = filter_input(INPUT_SERVER, "REMOTE_ADDR",  FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);


if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST' && $com_author_id = $user_log_id) {
    if ($com_content != null) {

        $stmt_com = $dbconnect->prepare("INSERT INTO `profile-comments` (com_profile_id, com_content, com_author_id, com_ip) VALUES (?,?,?,?)") or die($dbconnect->error);
        $stmt_com->bind_param('isis', $com_profile_id, $com_content, $com_author_id, $com_ip);
        $stmt_com->execute();

        $stmt_com->close();
    } else {
        echo "Comments cannot be empty";
    }

    $stmt_profile_name = $dbconnect->prepare("SELECT user_name FROM `user-accounts` WHERE user_id = ?");
    $stmt_profile_name->bind_param('i', $com_profile_id);
    $stmt_profile_name->execute();
    $result_profile_name = $stmt_profile_name->get_result();

    while ($row_profile_name = $result_profile_name->fetch_assoc()) {
        $profile_name = $row_profile_name['user_name'];
    }
}

header("Location: //dashmash.ddns.net/profile.php?id=$profile_name#topic-reply"); /* Redirects back to profile */
exit;
