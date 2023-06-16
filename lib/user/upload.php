<?php

include '../commons/connect.php';
include '../commons/session.php';
require_once '../forum_functionality/bbcode_parser.php';

/* ///////////////////////////// COLOR UPLOAD ////////////////////////// */
if ((filter_input(INPUT_POST, "icon-color")) != null) {
    $icon_color = $dbconnect->prepare("UPDATE `user-profile` SET profile_icon_color = ? WHERE profile_user_id = ?") or die($dbconnect->error);
    $icon_color->bind_param('ii', filter_input(INPUT_POST, "icon-color"), $user_log_id);
    $icon_color->execute();
    $icon_color->close();
}

if ((filter_input(INPUT_POST, "link-color")) != null) {
    $link_color = $dbconnect->prepare("UPDATE `user-profile` SET profile_link_color = ? WHERE profile_user_id = ?") or die($dbconnect->error);
    $link_color->bind_param('ii', filter_input(INPUT_POST, "link-color"), $user_log_id);
    $link_color->execute();
    $link_color->close();
}

if ((filter_input(INPUT_POST, "bg-color")) != null) {
    $bg_color = $dbconnect->prepare("UPDATE `user-profile` SET profile_bg_color = ? WHERE profile_user_id = ?") or die($dbconnect->error);
    $bg_color->bind_param('ii', filter_input(INPUT_POST, "bg-color"), $user_log_id);
    $bg_color->execute();
    $bg_color->close();
}
if ((filter_input(INPUT_POST, "part-color")) != null) {
    $part_color = $dbconnect->prepare("UPDATE `user-profile` SET profile_part_color = ? WHERE profile_user_id = ?") or die($dbconnect->error);
    $part_color->bind_param('ii', filter_input(INPUT_POST, "part-color"), $user_log_id);
    $part_color->execute();
    $part_color->close();
}

/*  ////////////////////////// BACKGROUND, BANNER, AVATAR UPLOAD  ////////////////////////////////////////////////////// */

$save_directory_avi = "uploads/avatar/";
$save_directory_bg = "uploads/bg/";
$save_directory_ban = "uploads/banner/";

/* //////////////////////////////////// BACKGROUND ///////////////////////////////// */
if (!empty($_FILES["bg-upload"])) {
    $bg = basename($_FILES["bg-upload"]["name"]);
    $hashed_bg = hash("md5", $bg); /* Hash BG */
    $bg_extension = explode(".", $_FILES["bg-upload"]["name"]);  /* Get file extension, to be added to new hashed filename */
    $target_file_bg = $save_directory_bg . $hashed_bg . "." . end($bg_extension);
}

/* //////////////////////////////////// AVATAR ////////////////////////////////////////// */
if (!empty($_FILES["avatar-upload"])) {
    $avi = basename($_FILES["avatar-upload"]["name"]);
    $hashed_avi = hash("md5", $avi); /* Hash avatar */
    $avi_extension = explode(".", $_FILES["avatar-upload"]["name"]); /* Get file extension, to be added to new hashed filename */
    $target_file_avi = $save_directory_avi . $hashed_avi . "." . end($avi_extension);
}

/* //////////////////////////////////// BANNER ////////////////////////////////////////// */
if (!empty($_FILES["banner-upload"])) {
    $ban = basename($_FILES["banner-upload"]["name"]);
    $hashed_ban = hash("md5", $ban); /* Hash banner */
    $ban_extension = explode(".", $_FILES["banner-upload"]["name"]); /* Get file extension, to be added to new hashed filename */
    $target_file_ban = $save_directory_ban . $hashed_ban . "." . end($ban_extension);
}

$upload = true;

$avi_file_type = pathinfo($target_file_avi, PATHINFO_EXTENSION);
$bg_file_type = pathinfo($target_file_bg, PATHINFO_EXTENSION);
$ban_file_type = pathinfo($target_file_ban, PATHINFO_EXTENSION);

// Check if image is real
if (isset($_GET["submit"])) {
    $check_bg = getimagesize($_FILES["bg-upload"]["tmp_name"]);
    $check_avi = getimagesize($_FILES["avatar-upload"]["tmp_name"]);
    $check_ban = getimagesize($_FILES["banner-upload"]["tmp_name"]);
    if ($check_bg !== false || $check_avi !== false || $check_ban !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $upload = true;
    } else {
        echo "One or more of your files is not an image.";
        $upload = false;
    }
}

// Check if file already exists
if (file_exists($target_file_bg) && file_exists($target_file_avi) && file_exists($target_file_ban)) {
    echo "One or more of your images already exist. ";
    $upload = false;
}

// Check file size
if ($_FILES["bg-upload"]["size"] > 2000000 || $_FILES["avatar-upload"]["size"] > 1024000 || $_FILES["banner-upload"]["size"] > 2000000) {
    echo "One or more of your images are too large.";
    $upload = false;
}

/* ////////////////////////////////////// MOVE UPLOADED  FILE FROM TMP TO DIR AND INSERT HASH TO DB  ///////////////////////////// */


/* Check if user has a profile attached to them */
$stmt_confirm_id = $dbconnect->prepare("SELECT profile_user_id FROM `user-profile` WHERE profile_user_id = ?");
$stmt_confirm_id->bind_param('i', $user_log_id);
$stmt_confirm_id->execute();
$result_confirm_id = $stmt_confirm_id->get_result();

if (mysqli_num_rows($result_confirm_id) > 0) {
    /**/
} else {
    /* Add their profile */
    $stmt_add_profile = $dbconnect->prepare("INSERT INTO `user-profile` (profile_user_id) VALUES (?) ");
    $stmt_add_profile->bind_param('i', $user_log_id);
    $stmt_add_profile->execute();
    $stmt_add_profile->close();
}

$stmt_confirm_id->close();

if ($upload == false) {
    echo " </br></br>Your file was not uploaded.";
} else {
    if ($bg) {

        /* ////////////////////////////////////// INSERT HASHED BG TO DB ///////////////////////////// */

        $stmt_hash_insert_bg = $dbconnect->prepare("UPDATE `user-profile` SET profile_bg = ? WHERE profile_user_id = ?");

        $insert_bg = $hashed_bg . "." . end($bg_extension);

        $stmt_hash_insert_bg->bind_param("ss", $insert_bg, $user_log_id);
        $stmt_hash_insert_bg->execute();
        $stmt_hash_insert_bg->close();


        move_uploaded_file($_FILES["bg-upload"]["tmp_name"], $target_file_bg);
        echo "</br>The background image " . $bg . " has been uploaded";
    } if ($avi) {

        /* ////////////////////////////////////// INSERT HASHED AVATAR TO DB ///////////////////////////// */


        $stmt_hash_insert_avi = $dbconnect->prepare("UPDATE `user-profile` SET profile_avatar = ? WHERE profile_user_id = ?");

        $insert_avi = $hashed_avi . "." . end($avi_extension);

        $stmt_hash_insert_avi->bind_param("ss", $insert_avi, $user_log_id);
        $stmt_hash_insert_avi->execute();
        $stmt_hash_insert_avi->close();


        move_uploaded_file($_FILES["avatar-upload"]["tmp_name"], $target_file_avi);
        echo "</br>The avatar " . $avi . " has been uploaded.";
    } if ($ban) {



        /* ////////////////////////////////////// INSERT HASHED BANNER TO DB ///////////////////////////// */


        $stmt_hash_insert_ban = $dbconnect->prepare("UPDATE `user-profile` SET profile_banner = ? WHERE profile_user_id = ?");

        $insert_ban = $hashed_ban . "." . end($ban_extension);

        $stmt_hash_insert_ban->bind_param("ss", $insert_ban, $user_log_id);
        $stmt_hash_insert_ban->execute();
        $stmt_hash_insert_ban->close();


        move_uploaded_file($_FILES["banner-upload"]["tmp_name"], $target_file_ban);
        echo "</br>The banner " . $ban . " has been uploaded.";
    }
}



/*  //////////////////////////////////// PROFILE CONTENT (ABOUT ME, SOCIAL LINKS)  ////////////////////////////////////////////////////// */



if (!empty(filter_input(INPUT_POST, "content"))) {
    /* Content */
    $content = BBcode(htmlspecialchars($_POST["content"]));
    $content_pure = filter_input(INPUT_POST, "content", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $save_content = $dbconnect->prepare("UPDATE `user-profile` SET profile_content = ?, profile_editor_content = ? WHERE profile_user_id = ?") or die($dbconnect->error);
    $save_content->bind_param('ssi', $content, $content_pure, $user_log_id);
    $save_content->execute();
    $save_content->close();
}

if (!empty(filter_input(INPUT_POST, "discord", FILTER_SANITIZE_FULL_SPECIAL_CHARS))) {
    /* Discord */
    $discord = filter_input(INPUT_POST, "discord", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $save_discord = $dbconnect->prepare("UPDATE `user-profile` SET profile_discord = ? WHERE profile_user_id = ?") or die($dbconnect->error);
    $save_discord->bind_param('si', $discord, $user_log_id);
    $save_discord->execute();
    $save_discord->close();
}

if (!empty(filter_input(INPUT_POST, "twitter", FILTER_SANITIZE_FULL_SPECIAL_CHARS))) {
    /* Twitter */
    $twitter = filter_input(INPUT_POST, "twitter", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $save_twitter = $dbconnect->prepare("UPDATE `user-profile` SET profile_twitter = ? WHERE profile_user_id = ?") or die($dbconnect->error);
    $save_twitter->bind_param('si', $twitter, $user_log_id);
    $save_twitter->execute();
    $save_twitter->close();
}

if (!empty(filter_input(INPUT_POST, "youtube", FILTER_SANITIZE_FULL_SPECIAL_CHARS))) {
    /* YouTube */
    $youtube = filter_input(INPUT_POST, "youtube", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $save_youtube = $dbconnect->prepare("UPDATE `user-profile` SET profile_youtube = ? WHERE profile_user_id = ?") or die($dbconnect->error);
    $save_youtube->bind_param('si', $youtube, $user_log_id);
    $save_youtube->execute();
    $save_youtube->close();
}

if (!empty(filter_input(INPUT_POST, "steam", FILTER_SANITIZE_FULL_SPECIAL_CHARS))) {
    /* Steam */
    $steam = filter_input(INPUT_POST, "steam". FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $save_steam = $dbconnect->prepare("UPDATE `user-profile` SET profile_steam = ? WHERE profile_user_id = ?") or die($dbconnect->error);
    $save_steam->bind_param('si', $steam, $user_log_id);
    $save_steam->execute();
    $save_steam->close();
}

if (!empty(filter_input(INPUT_POST, "twitch", FILTER_SANITIZE_FULL_SPECIAL_CHARS))) {
    /* Steam */
    $twitch = filter_input(INPUT_POST, "twitch", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $save_twitch = $dbconnect->prepare("UPDATE `user-profile` SET profile_twitch = ? WHERE profile_user_id = ?") or die($dbconnect->error);
    $save_twitch->bind_param('si', $twitch, $user_log_id);
    $save_twitch->execute();
    $save_twitch->close();
}


$dbconnect->close();

header("Location: https://dashmash.ddns.net/profile.php?id=$user_log_name");  /* Redirects back to profile */
exit;
