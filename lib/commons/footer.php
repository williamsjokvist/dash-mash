<footer>
    <div>
        <img src="//dashmash.ddns.net/assets/img/logo/logo_main.png" alt="logo"/>
        <section>
            <h5>Navigation</h5>
            <ul>
                <li><a href='//dashmash.ddns.net/?action=index'>Home</a><span>E.T. Go Home</span></li>
                <li><a href="//dashmash.ddns.net/?action=play">Play</a><span>Play via the Browser</span></li>
                <li><a href="//dashmash.ddns.net/?action=leaderboards">Leaderboards</a><span>Score and other rankings</span></li>                
                <li><a href="//dashmash.ddns.net/forum.php">Community Forum</a></li>
                <li><a href="//dashmash.ddns.net/?action=about">About</a></li>
                <li><a href="//dashmash.ddns.net/?action=sitemap">Sitemap</a></li>
            </ul>
        </section>
        <section>
            <h5>Social</h5>
            <ul>
                <li><a href="//discordapp.com/invite/EdwRuY">Discord</a><span>Discord Invite</span></li>
                <li><a href="//www.youtube.com">YouTube</a></li>
                <li><a href="//www.twitter.com">Twitter</a></li>
                <li><a href="//www.twitch.tv/">Twitch</a></li>
                <li><a href="//www.steamcommunity.com/">Steam Group</a></li>
            </ul>
        </section>
        <div>
            <p>
                <strong>© 2018 All rights reserved</strong>  | <a href='//dashmash.ddns.net/?action=terms'>Terms & Conditions</a>
            </p>
            <p class='stripes'>
                <?php
                $stmt_reg_users = $dbconnect->prepare("SELECT user_id FROM `user-accounts`") or die($dbconnect->error);
                $stmt_reg_users->execute();
                $reg_users = $stmt_reg_users->get_result();
                $stmt_reg_users->close();

                $stmt_latest_user = $dbconnect->prepare("SELECT user_name FROM `user-accounts` ORDER BY user_joindate DESC LIMIT 1") or die($dbconnect->error);
                $stmt_latest_user->execute();
                $stmt_latest_user->bind_result($latest_user);
                $stmt_latest_user->fetch();
                $stmt_latest_user->close();

                $stmt_online_count = $dbconnect->prepare("SELECT user_online FROM `user-accounts` WHERE user_online = 1")or die($dbconnect->error);
                $stmt_online_count->execute();
                $result_online_count = $stmt_online_count->get_result();
                $stmt_online_count->close();

                echo "<span>Latest User: <a href='//dashmash.ddns.net/profile.php?id=$latest_user'>$latest_user</a></span> |"
                . "<span>Users Online: <mark>" . mysqli_num_rows($result_online_count) . "</mark></span> |"
                . "<span>Registered Users: <mark>" . mysqli_num_rows($reg_users) . "</mark></span>";
                ?>
            </p>
        </div>
    </div>
	<a href='javascript:void(0)' id='auto-up'></a>
</footer>