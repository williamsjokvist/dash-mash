<header>
        <a href="//dashmash.ddns.net/?action=index" title="Go Home">Dash & Mash ~ Go Home</a>

    <?php

    if (preg_match('/board.php/', $page) || preg_match('/topic.php/', $page)) {
        include "../lib/commons/notification-bar.php";
    } else {
        include "lib/commons/notification-bar.php";
    }

    /* SHOW ACCOUNT MSG IF NOT REFRESHED */

    $_SESSION['refresh'] = false;

    if (isset($_SESSION['acc_msg'])) {
        /* There's an account msg */
        echo "<dialog class='account_msg' open><h4>Alert</h4>" . $_SESSION['acc_msg'] . "</dialog>";
        $_SESSION['refresh'] = true;
    }

    if ($_SESSION['refresh'] == true) {
        $_SESSION['acc_msg'] = null;
    }
    ?>

</header>