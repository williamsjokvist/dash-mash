<?php
include 'lib/commons/connect.php'; include 'lib/commons/session.php'; require_once 'lib/commons/packer.php';
ob_start();
include 'lib/commons/head.php'; include 'lib/commons/action.php';
$content = ob_get_contents();
ob_end_clean();

echo PackContent($content);
include 'lib/commons/footer.php'; echo "</body></html>";
$dbconnect->close();