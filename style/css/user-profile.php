<?php

header("Content-type: text/css");
include "../../lib/commons/connect.php";
$user_id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

/* ///////////////////////// FETCH PROFILE BANNER, BACKGROUND & COLOR  ///////////////////////// */

$stmt = $dbconnect->prepare("SELECT profile_bg, profile_banner, profile_avatar, profile_icon_color, profile_bg_color, profile_link_color, profile_part_color, profile_bar_color FROM `user-profile` WHERE profile_user_id = ? LIMIT 1");
$stmt->bind_param('s', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if (mysqli_num_rows($result) > 0) {
    while ($row = $result->fetch_assoc()) {
        $profile_bg = $row["profile_bg"];
        $profile_banner = $row["profile_banner"];
        $link_color = $row["profile_link_color"];
        $icon_color = $row["profile_icon_color"];
        $bg_color = $row["profile_bg_color"];
        $part_color = $row["profile_part_color"];
        $bar_color = $row["profile_bar_color"];

        /* ADD BG CSS */
        if ($profile_bg != null && $profile_bg != "default.jpg") {
            echo ".user-profile>main>section{ background: url(//dashmash.ddns.net/assets/img/filter/filter_line.png) top center repeat-y, url(//dashmash.ddns.net/lib/user/uploads/bg/$profile_bg) center center no-repeat #000;"
            . "background-size: 200vh auto!important; background-attachment: fixed;} ";
        } else if ($profile_bg == "default.jpg") {
            echo ".user-profile>main>section{background:url(//dashmash.ddns.net/lib/user/uploads/bg/$profile_bg) repeat;background-size: auto!important;}";
        }

        /* ADD BANNER CSS */
        if ($profile_banner != null && $profile_banner != "default.jpg") {
            echo "banner-image{ background-image: url(//dashmash.ddns.net/lib/user/uploads/banner/$profile_banner);}";
        } else if ($profile_banner == "default.jpg") {
            echo "banner-image{background:url(//dashmash.ddns.net/lib/user/uploads/banner/$profile_banner) repeat;    background-size: auto!important;}";
        }

        /* ADD ICON COLOR CSS */
        if ($icon_color > 0) {
            echo ".profile-container h3::before, .subsection h4::before, .profile-container>section>header>a{";

            if ($icon_color == 1) {
                echo "filter: hue-rotate(86.25deg) saturate(175%) brightness(145%);";
            } else if ($icon_color == 2) {
                echo "filter: hue-rotate(549.25deg) saturate(50%) brightness(100%);";
            } else if ($icon_color == 3) {
                echo "filter: hue-rotate(244.25deg) saturate(266%) brightness(110%);";
            } else if ($icon_color == 4) {
                echo "filter: hue-rotate(-91.75deg) saturate(192%) brightness(120%);";
            } else if ($icon_color == 5) {
                echo "filter: hue-rotate(320deg) saturate(900%) brightness(110%);";
            } else if ($icon_color == 6) {
                echo "    filter: hue-rotate(307.25deg) saturate(475%) brightness(145%);";
            }

            echo "}";
        }

        /* ADD BG COLOR CSS */
        if ($bg_color > 0) {
            echo ".profile-container{";

            if ($bg_color == 1) {
                echo "background: rgba(26, 255, 0, 0.3)!important;";
            } else if ($bg_color == 2) {
                echo "background: rgba(0, 53, 255, 0.3)!important;";
            } else if ($bg_color == 3) {
                echo "background: rgba(159, 0, 255, 0.3)!important;";
            } else if ($bg_color == 4) {
                echo "background: rgba(218, 0, 255, 0.3)!important;";
            } else if ($bg_color == 5) {
                echo "background: rgba(255, 0, 0, 0.3)!important;";
            } else if ($bg_color == 6) {
                echo "background: rgba(255, 255, 255, 0.3)!important;";
            }

            echo "}";

            echo "#topic-reply textarea:focus, .subsection textarea:focus{";

            if ($bg_color == 1) {
                echo "border-color: #57ff3a!important;";
            } else if ($bg_color == 2) {
                echo "border-color:#6eb4ff!important; ";
            } else if ($bg_color == 3) {
                echo "border-color: #d03aff!important;";
            } else if ($bg_color == 4) {
                echo "border-color: #f95df9!important;";
            } else if ($bg_color == 5) {
                echo "border-color: #ff4343!important;";
            }

            echo "}";
        }

        /* ADD LINK COLOR CSS */
        if ($link_color > 0) {
            echo "profile-bar a, main a{";

            if ($link_color == 1) {
                echo "color: #57ff3a!important;";
            } else if ($link_color == 2) {
                echo "color: #6eb4ff!important; ";
            } else if ($link_color == 3) {
                echo "color:#d03aff!important; ";
            } else if ($link_color == 4) {
                echo "color: #f95df9!important; ";
            } else if ($link_color == 5) {
                echo "color:#ff4343!important; ";
            }

            echo "}";
        }

        if ($part_color > 0) {
            echo ".profile-container>section{";
            
            if ($part_color == 1) {
                echo "background: #18291a";
            } else if ($part_color == 2) {
                echo "background: #1e1829";
            } else if ($part_color == 3) {
                echo "background: #391d4a";
            } else if ($part_color == 4) {
                echo "background: #351c2d";
            } else if ($part_color == 5) {
                echo "background: #421a1a";
            }

            echo "}#topic-reply, .editor, .content-showcase{background-color: rgba(0, 0, 0, 0.5);}";
        }
    }
} else {
    echo "banner-image{background:none;}";
}
$stmt->close();
