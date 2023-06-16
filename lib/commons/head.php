<!DOCTYPE html>
<html lang='en'>
    <head>
        <?php

        if (preg_match('/board.php/', $page) || preg_match('/topic.php/', $page)) {
            echo '<script src="../script/dialog-polyfill.js"></script><script src="../script/polyfill.js"></script><script src="../script/webcomponents.js"></script>';
        } else {
            echo '<script src="script/dialog-polyfill.js"></script><script src="script/polyfill.js"></script><script src="script/webcomponents.js"></script>';
        }
        ?>

        <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0" user-scalable="yes"/>
        <meta http-equiv="x-UA-Compatible" content="IE-edge,chrome=1"/>
        <meta name="keywords" content="dash,mash,dash & mash, dashmash" />
        <meta name="description" content="Dash & Mash Web Game!"/>

        <link href="//dashmash.ddns.net/style/css/common.css" rel="stylesheet"/>
        <link href="//dashmash.ddns.net/style/css/fonts.css" rel="stylesheet"/>
        <link href="//dashmash.ddns.net/style/css/preload.css" rel="stylesheet"/>
        <link href="//dashmash.ddns.net/style/css/animations.css" rel="stylesheet"/>
        <link href="//dashmash.ddns.net/style/css/nav.css" rel="stylesheet"/>
        <link href="//dashmash.ddns.net/style/css/sections.css" rel="stylesheet"/>
        <link href="//dashmash.ddns.net/style/css/icons.css" rel="stylesheet"/>
        <link href="//dashmash.ddns.net/style/css/fonts.css" rel="stylesheet"/>
        <link href="//dashmash.ddns.net/style/css/sitemap.css" rel="stylesheet"/>

        <script src="//dashmash.ddns.net/script/common.js"></script>
        <script src="//dashmash.ddns.net/script/customs.js"></script>

        <?php
        if ($user_log_id == null || !$user_log_id) {
            echo "<script src='//www.google.com/recaptcha/api.js' defer></script>";
        } else {
            echo "<script src='//dashmash.ddns.net/lib/user/mark-read.js' defer></script>";
        }
        ?>