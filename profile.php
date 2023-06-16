<?php

include "lib/commons/connect.php";
include 'lib/commons/session.php';
include 'lib/forum_functionality/time_elapse.php';
require_once 'lib/commons/packer.php';

$profile_user_name = filter_input(INPUT_GET, "id", FILTER_SANITIZE_SPECIAL_CHARS);


if ($profile_user_name) {

    /* ////////////////////// FETCH INFO IF USER EXISTS  /////////////////////////////// */
    $stmt_user_check = $dbconnect->prepare("SELECT user_name, user_joindate, user_level, user_id, user_online FROM `user-accounts` WHERE user_name  = ?");
    $stmt_user_check->bind_param('s', $profile_user_name);
    $stmt_user_check->execute();
    $result_user_check = $stmt_user_check->get_result();

    if (mysqli_num_rows($result_user_check) > 0) {

        $user_exists = true;

        while ($row_user = $result_user_check->fetch_assoc()) {
            $user_name = $row_user["user_name"];
            $user_joindate = new DateTime($row_user["user_joindate"]);
            $user_level = $row_user["user_level"];
            $user_id = $row_user["user_id"];
            $user_online = $row_user["user_online"];
        }
    }

    $stmt_user_check->close();

    /* CHECK IF PROFILE BELONGS TO THE LOGGED IN USER */
    if (isset($user_log_name) && $user_log_name == $user_name) {
        $user_session = true;
    } else {
        $user_session = false;
    }

    /* Check if logged in user is friends with the user the profile belongs to */
    $stmt_iffriends = $dbconnect->prepare("(SELECT friend_status FROM friends "
            . "WHERE requested_id = ?) "
            . "UNION "
            . "(SELECT friend_status FROM friends "
            . "WHERE requester_id = ?)") or die($dbconnect->error);
    $stmt_iffriends->bind_param('ii', $user_id, $user_log_id);
    $stmt_iffriends->execute();
    $result_iffriends = $stmt_iffriends->get_result();
    if (mysqli_num_rows($result_iffriends) > 0) {
        $friend_state = true;
    } else {
        $friend_state = false;
    }

    $stmt_iffriends->close();
}

ob_start();

echo "<!DOCTYPE html><html lang='en'><head>";
include 'lib/commons/head.php';
if ($user_session) {
    echo '<script src="script/dragdrop.min.js" defer></script><script src="script/profile.js" defer></script>';
}
echo '<script src="script/desktop.js" defer></script>'
 . '<link href="style/css/profiles.css" rel="stylesheet"/>'
 . '<link href="style/css/user-profile.php?id=' . $user_id . '" rel="stylesheet"/>'
 . "<title>$user_name ~ Dash & Mash</title></head>";

echo "<body class='user-profile'>";
if ($user_exists == true) {
    include "lib/user/_profile.php";
} else {
    header("Location: //dashmash.ddns.net/?action=error"); /* Redirects to the error page */
    exit();
}

include 'lib/commons/footer.php';
echo "</body></html>";

$content = ob_get_contents();
ob_end_clean();

echo PackContent($content);
$dbconnect->close();