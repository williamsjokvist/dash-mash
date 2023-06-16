<?php
include '../commons/connect.php';
include '../commons/session.php';
$report_id = filter_input(INPUT_GET, "report_id", FILTER_SANITIZE_NUMBER_INT);

if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'GET' && isset($_SESSION["logged_in"])) {
    $report_status_set = 1;
    $report_status = $dbconnect->prepare("UPDATE `user-reports` SET report_status = ? WHERE report_id = ?") or die($dbconnect->error);
    $report_status->bind_param('ii', $report_status_set, $report_id);
    $report_status->execute();
    $report_status->close();

    header("Location: https://dashmash.ddns.net/"); 
    exit;
}