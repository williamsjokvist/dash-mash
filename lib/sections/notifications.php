<?php

    /* Notification bell */
    $stmt_pmread = $dbconnect->prepare("SELECT pm_read FROM `personal-message` WHERE pm_read = 0 AND message_reciver_id = ?");
    $stmt_pmread->bind_param('i', $user_log_id);
    $stmt_pmread->execute();
    $result_pmread = $stmt_pmread->get_result();
    
    $stmt_fr = $dbconnect->prepare("SELECT * FROM friends WHERE friend_status = 0 AND requested_id = ?");
    $stmt_fr->bind_param('i', $user_log_id);
    $stmt_fr->execute();
    $result_fr = $stmt_fr->get_result();

    if ($user_log_level == $admin) {
        $report_status = 0;
        /*Fetch reports*/
        $stmt_report_info = $dbconnect->prepare("SELECT `user-reports`.reporter_user_id, `user-reports`.report_reason, `user-reports`.report_date, `user-accounts`.user_name, `user-reports`.report_id FROM `user-reports` INNER JOIN `user-accounts` ON `user-reports`.reported_user_id=`user-accounts`.user_id WHERE report_status = ?") or die($dbconnect->error);
        $stmt_reporter_info = $dbconnect->prepare("SELECT user_name FROM `user-accounts` WHERE user_id = ?") or die($dbconnect->error);
        $stmt_report_info->bind_param('i', $report_status);
        $stmt_report_info->execute();
        $stmt_report_info->bind_result($reporter_user_id, $report_reason, $report_date, $reported_user_name, $report_id);
        $result_report_info = $stmt_report_info->get_result();
        $stmt_report_info->close();

        $notice_report = mysqli_num_rows($result_report_info);
    } else {
        $notice_report = 0;
    }

    echo "<a href='javascript:void(0)' type='toggler' id='bell' title='Notifications'>";
    if (mysqli_num_rows($result_pmread) > 0 || mysqli_num_rows($result_fr) > 0) {
        echo "<span>";
        echo mysqli_num_rows($result_pmread) + mysqli_num_rows($result_fr) + $notice_report;
        echo "</span>";
    }
    echo "</a><section class='notifications' data-toggled><header><h4>Notifications</h4></header><ol>";

    /* gets users personal messages */
    $stmt_pm = $dbconnect->prepare("SELECT `personal-message`.message_id, `personal-message`.message, `personal-message`.message_date, `user-accounts`.user_name FROM `personal-message` LEFT JOIN `user-accounts` ON `personal-message`.message_sender_id=`user-accounts`.user_id WHERE pm_read = 0 AND message_reciver_id = ? ORDER BY message_date DESC") or die($dbconnect->error);
    $stmt_pm->bind_param('i', $user_log_id);
    $stmt_pm->execute();
    $stmt_pm->bind_result($pm, $pm_id, $pm_date, $pm_sender);
    $result_pm = $stmt_pm->get_result();
    $stmt_pm->close();

    if (mysqli_num_rows($result_pm) > 0) {
        while ($row_pm = $result_pm->fetch_assoc()) {
            $pm = $row_pm['message'];
            $pm_id = $row_pm["message_id"];
            $pm_date = new DateTime($row_pm['message_date']);
            $pm_sender = $row_pm['user_name'];
            echo "<li data-pm='$pm_id'><header><a href='//dashmash.ddns.net/profile.php?id=$pm_sender'>$pm_sender</a><div><time>" . $pm_date->format('d, M Y') . "</time><a class='mark-read' href='javascript:void(0)' title='Mark as read'></a></div></header>"
            . "<p>$pm</p> </li>";
        }
    }
    
    /*Fetch friend requests*/
    $stmt_friend_request = $dbconnect->prepare("SELECT friends.requester_id, friends.request_id, friends.friend_date_status, `user-accounts`.user_name FROM friends INNER JOIN `user-accounts` ON `user-accounts`.user_id = friends.requester_id  WHERE requested_id = ? AND friend_status = 0") or die($dbconnect->error);
    $stmt_friend_request->bind_param("i", $user_log_id);
    $stmt_friend_request->execute();
    $result_friend_request = $stmt_friend_request->get_result();
    $stmt_friend_request->close();
    
    if (mysqli_num_rows($result_friend_request) > 0){
        while($row_friend_request = $result_friend_request->fetch_assoc()){
            $request_id = $row_friend_request["request_id"];
            $requester_id = $row_friend_request["requester_id"];
            $requester_user_name = $row_friend_request["user_name"];
            $request_date = new DateTime($row_friend_request["friend_date_status"]);
            
            echo "<li data-fr='$request_id'><header>from <a href='//dashmash.ddns.net/profile.php?id=$requester_user_name'>$requester_user_name</a><time>" . $request_date->format('d, M Y') . "</time></header><footer><a data-fr-accept=1>Accept</a><a data-fr-accept=2>Deny</a></footer></li>";
        }
    }
    
    
    if ($user_log_level == $admin) {
        if (mysqli_num_rows($result_report_info) > 0) {
            while ($row_report_info = $result_report_info->fetch_assoc()) {
                $reporter_user_id = $row_report_info["reporter_user_id"];
                $reported_user_name = $row_report_info["user_name"];
                $report_reason = $row_report_info["report_reason"];
                $report_date = new DateTime($row_report_info["report_date"]);
                $report_id = $row_report_info["report_id"];
                $stmt_reporter_info->bind_param('i', $reporter_user_id);
                $stmt_reporter_info->execute();
                $stmt_reporter_info->bind_result($reporter_user_name);
                $result_reporter_info = $stmt_reporter_info->get_result();
                $stmt_reporter_info->close();
                if (mysqli_num_rows($result_reporter_info) > 0) {
                    while ($row_reporter_info = $result_reporter_info->fetch_assoc()) {
                        $reporter_user_name = $row_reporter_info["user_name"];
                        echo "<li class='report'><header><a href='//dashmash.ddns.net/profile.php?id=$reported_user_name'>$reported_user_name</a> by <a href='//dashmash.ddns.net/profile.php?id=$reporter_user_name'>$reporter_user_name</a><div><time>" . $report_date->format('d, M Y') . "</time><a href='//dashmash.ddns.net/lib/user/resolve.php?report_id=$report_id'></a></div></header>"
                        . "<p>Reason: $report_reason</p> </a></li>";
                    }
                }
            }
        }
    }
    
    echo "</ol></section>";