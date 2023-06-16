<section class="wrapper friends" data-toggled>
    <div>
        <header><h3>Friends</h3></header>

        <?php
        $fetch_friends = $dbconnect->prepare("(SELECT friends.friend_date_status, `user-accounts`.`user_name`, `user-accounts`.`user_online`, `user-profile`.`profile_avatar` FROM friends "
                . "INNER JOIN `user-accounts` ON `user-accounts`.`user_id` = friends.requester_id "
                . "INNER JOIN `user-profile` ON `user-profile`.`profile_user_id` = friends.requester_id "
                . "WHERE friends.requested_id = ? AND friends.friend_status = 1) "
                . "UNION "
                . "(SELECT friends.friend_date_status, `user-accounts`.`user_name`, `user-accounts`.`user_online`, `user-profile`.`profile_avatar` FROM friends "
                . "INNER JOIN `user-accounts` ON `user-accounts`.`user_id` = friends.requested_id "
                . "INNER JOIN `user-profile` ON `user-profile`.`profile_user_id` = friends.requested_id "
                . "WHERE friends.requester_id = ? AND friends.friend_status = 1)") or die($dbconnect->error);
        $fetch_friends->bind_param('ii', $user_log_id, $user_log_id);
        $fetch_friends->execute();
        $result_friends = $fetch_friends->get_result();

        if (mysqli_num_rows($result_friends) > 0) {
            echo "<ul>";
            while ($row_friends = $result_friends->fetch_assoc()) {
                $friend_name = $row_friends['user_name'];
                $friend_online = $row_friends['user_online'];
                $friend_avatar = $row_friends['profile_avatar'];
                $friends_since = new DateTime($row_friends['friend_date_status']);

                echo "<li><a href='//dashmash.ddns.net/profile.php?id=$friend_name'><img src='//dashmash.ddns.net/lib/user/uploads/avatar/$friend_avatar'/>";
                echo "<div class='user-online' style='background-color:";
                if ($friend_online == 1) {
                    echo "#10e910";
                } else if ($friend_online == 0) {
                    echo "#ec2a24";
                }
                echo "'></div><span>$friend_name</span></a><span>Friends since: <time>" . $friends_since->format('d M Y') . "</time></span></li>";
            }
            echo "</ul>";
        }

        $fetch_friends->close();
        ?>
        <!--  TODO: PM and delete friends directly from list 
        <div>
                      <a href="javascript:void(0)" class="fat">PM</a>
                      <a href="javascript:void(0)" class="fat">Delete</a>
                  </div>-->
    </div>
</section>