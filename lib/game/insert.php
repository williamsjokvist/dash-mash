<?php

include "../commons/connect.php";
include '../commons/session.php';

if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST' && $user_log_id && $user_log_level != 10) {

    $score = filter_input(INPUT_POST, "score". FILTER_SANITIZE_NUMBER_INT);

    /* Check if user has a rank attached to them */
    $stmt_confirm_id = $dbconnect->prepare("SELECT * FROM `rankings` WHERE rank_user = ?") or die($dbconnect->error);
    $stmt_confirm_id->bind_param('i', $user_log_id);
    $stmt_confirm_id->execute();
    $result_confirm_id = $stmt_confirm_id->get_result();
    $stmt_confirm_id->close();

    if (!mysqli_num_rows($result_confirm_id) > 0) {
        /* Add their rank row */
        $stmt_add_profile = $dbconnect->prepare("INSERT INTO `rankings` (rank_user, rank_score) VALUES (?, ?) ") or die($dbconnect->error);
        $stmt_add_profile->bind_param('ii', $user_log_id, $score);
        $stmt_add_profile->execute();
        $stmt_add_profile->close();
        
    } else {
        /* Update score instead if score > score in db */
        $currrank_check = $dbconnect->prepare("SELECT rank_score FROM `rankings` WHERE rank_user = ?") or die($dbconnect->error);
        $currrank_check->bind_param('i', $user_log_id);
        $currrank_check->execute();
        $result_currrank_check = $currrank_check->get_result();

        while ($row_currank = $result_currrank_check->fetch_assoc()) {
            $curr_rank_score = $row_currank["rank_score"];

            if ($score > $curr_rank_score) {
                $stmt_insert = $dbconnect->prepare("UPDATE `rankings` SET rank_score = ? WHERE rank_user = ?") or die($dbconnect->error);
                $stmt_insert->bind_param('ii', $score, $user_log_id);
                $stmt_insert->execute();
                $stmt_insert->close();
            }
        }

        
        echo "OK";
        $currrank_check->close();
    }
} else {
    echo "This score is illegitimate";
}

$dbconnect->close();
