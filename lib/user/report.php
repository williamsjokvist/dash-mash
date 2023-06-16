<?php
    include '../commons/connect.php';
    include '../commons/session.php';
    $reported_user_id = filter_input(INPUT_GET, "user", FILTER_SANITIZE_NUMBER_INT);
    $report_content = filter_input(INPUT_POST, "content", FILTER_SANITIZE_STRING);
    $reporter_user_ip = filter_input(INPUT_SERVER, "REMOTE_ADDR", FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    
    if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST' && $user_log_id && $user_log_level != 10) {
        if ($report_content != null){
            $query_report = $dbconnect->prepare("INSERT INTO `user-reports` (reported_user_id, report_reason, reporter_user_id, reporter_ip) VALUES (?, ?, ?, ?)");
            $query_report->bind_param('isis', $reported_user_id, $report_content, $user_log_id, $reporter_user_ip);
            $query_report->execute();
            $query_report->close();
            header("Location: https://dashmash.ddns.net/"); 
            exit;
            
        } else{

            echo $reporter_user_ip;

            echo "Report entries cannot be empty<br>";
            echo "<a href='https://dashmash.ddns.net/'>Go back</a>";
        }
    }
