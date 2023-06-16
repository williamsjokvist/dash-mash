<?php

include "../commons/connect.php";
include '../commons/session.php';

if ($user_log_id) {
    $stmt_game = $dbconnect->prepare("SELECT user_name, user_id FROM `user-accounts` WHERE user_id = ?") or die($dbconnect->error);
    $stmt_avi = $dbconnect->prepare("SELECT profile_avatar FROM `user-profile` WHERE profile_user_id = ?") or die($dbconnect->error);
    $stmt_score = $dbconnect->prepare("SELECT rank_score FROM `rankings` WHERE rank_user = ?") or die($dbconnect->error);

    $stmt_game->bind_param('i', $user_log_id);
    $stmt_avi->bind_param('i', $user_log_id);
    $stmt_score->bind_param('i', $user_log_id);

    $stmt_game->execute();
    $res_game = $stmt_game->get_result();

    $stmt_avi->execute();
    $res_avi = $stmt_avi->get_result();

    $stmt_score->execute();
    $res_score = $stmt_score->get_result();

    $json_game = $res_game->fetch_assoc();
    $json_game_2 = $res_avi->fetch_assoc();

    if ($json_game_2 == null) {
        $json_game_2 = array("default.png");
    }
    /*
      for ($i = 0; $i < count($json_game_2); $i++){
      echo $json_game[$i];
      } */

    if (mysqli_num_rows($res_score) > 0) {
        while ($row_score = $res_score->fetch_assoc()) {
            $json_game_3 = $row_score;
        }
        $json_result = array_merge($json_game, $json_game_2, $json_game_3);
    } else {
        $json_result = array_merge($json_game, $json_game_2);
    }

    print json_encode($json_result);

    $stmt_game->close();
    $stmt_score->close();
    $stmt_avi->close();
} else {
    echo "You must be logged in to access the game.";
}


$dbconnect->close();
