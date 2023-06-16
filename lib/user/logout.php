<?php

include "../commons/connect.php";
include "../commons/session.php";

/* Set user to offline */
$s = filter_input(INPUT_GET, 's', FILTER_SANITIZE_NUMBER_INT);

if ($user_log_id && $user_log_id == $s) {
    // Initialize
    session_start();

// Unset all of the session variables
    $_SESSION = array();

// Destroy 
    session_destroy();

    $stmt_set_offline = $dbconnect->prepare("UPDATE `user-accounts` SET user_online = 0 WHERE user_id = ?");
    $stmt_set_offline->bind_param('s', $s);
    $stmt_set_offline->execute();
    $stmt_set_offline->close();
}

header("Location: //dashmash.ddns.net/?action=index"); /* Redirects to the home page */
exit;
