<?php
echo "<notification-bar";
if (isset($_SESSION["logged_in"]) && isset($user_log_name)) {
    echo " class='logged-in'";
}
echo ">";
?>
<ul>
    <li>
        <button id="nav-button" aria-label="menu" aria-controls="navigation">
            <span>
                <span class="hamburger-inner"></span>
            </span>
        </button>
    </li>
    <li>
        <a href='//dashmash.ddns.net/?action=index' class="home-link" title="Home"></a>
    </li>
    <?php
    if (!isset($user_log_name)) {
        /* Not logged in  Sign in */
        echo "<li>";
        if (preg_match('/board.php/', $page) || preg_match('/topic.php/', $page)) {
            include "../lib/sections/signin.php";
            echo "</li><li>";
            include "../lib/sections/signup.php";
        } else {
            include "lib/sections/signin.php";
            echo "</li><li>";
            include "lib/sections/signup.php";
        }
        echo "</li>";
    }
    ?>
</ul>

<?php
if (isset($_SESSION["logged_in"]) && isset($user_log_name)) {
    echo "<ul><li>";
    if (preg_match('/board.php/', $page) || preg_match('/topic.php/', $page)) {
        include "../lib/sections/notifications.php";
        echo "</li><li>";
        include "../lib/sections/user-menu.php";
    } else {
        include "lib/sections/notifications.php";
        echo "</li><li>";
        include "lib/sections/user-menu.php";
    }
}
echo "</li></ul></notification-bar>";