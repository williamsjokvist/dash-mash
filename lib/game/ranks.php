<?php

include "../commons/connect.php";
include '../commons/session.php';

$res = array();
$usr = array();

$stmt_rank = $dbconnect->prepare("SELECT rank_user, rank_date, rank_score FROM `rankings` ORDER BY rank_score DESC LIMIT 10");
$stmt_user = $dbconnect->prepare("SELECT user_name FROM `user-accounts` WHERE user_id = ?");
$stmt_avatar = $dbconnect->prepare("SELECT profile_avatar FROM `user-profile` WHERE profile_user_id = ?");

$stmt_user->bind_param('i', $rank_user);
$stmt_avatar->bind_param('i', $rank_user);

$stmt_rank->execute();
$res_rank = $stmt_rank->get_result();


while ($row_rank = $res_rank->fetch_assoc()) {
    $rank_user = $row_rank["rank_user"];
    $rank = $row_rank;

    $stmt_user->execute();
    $user = $stmt_user->get_result()->fetch_assoc();

    $stmt_avatar->execute();
    $avatar = $stmt_avatar->get_result()->fetch_assoc();
    
    if (empty($avatar) || $avatar == null){$avatar = "default.png";}

    $usr[] = array($rank, $user, $avatar);
}


$res['rankings'] = $usr;
print json_encode($res);

$stmt_rank->close();
$stmt_user->close();
$stmt_avatar->close();
$dbconnect->close();