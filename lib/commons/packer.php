<?php

define('BASE_URL', '//dashmash.ddns.net/');
define('ROOT_DIR', dirname(__FILE__, 3));

if (preg_match('/board.php/', $page) || preg_match('/topic.php/', $page) || preg_match('/createboard.php/', $page) || preg_match('/createtopic.php/', $page)) {
    require_once '../packages/vendor/webphpack/src/WebPHPack.php';
} else {
    require_once 'packages/vendor/webphpack/src/WebPHPack.php';
}

use Phreak\WebPHPack;

function PackContent($content) {
    $packer = new WebPHPack($content);
    $packer->matchString = BASE_URL;
    $packer->jsPath = ROOT_DIR . '/script';
    $packer->cssPath = ROOT_DIR . '/style/css';
    $packer->outputPath = ROOT_DIR . '/cache';
    $packer->outputURL = BASE_URL . 'cache';
    $packer->caching = false;
    $packer->combineJS();
    $packer->combineCSS();

    return $packer->output();
}
