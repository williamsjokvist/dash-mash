<?php

$action = basename(filter_input(INPUT_GET, "action", FILTER_SANITIZE_STRING)); /* Get action from URL */ /* Basename translates the directory to a filename, which prevents fuccbois from trying to change directory  (: */

if (!empty($action)) {
    if (!file_exists("lib/pages/$action.php")) { /* If the page doesn't exist -> goes to homepage */
        $action = "index";
    }
    include("lib/pages/$action.php"); /* Include the page */
} else {
    include("lib/pages/index.php");
}