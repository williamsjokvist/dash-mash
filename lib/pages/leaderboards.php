<script>
if (screen && screen.width > 900) {
    var s = document.createElement("script");

    s.async = true;
    s.src = "//dashmash.ddns.net/script/paraxify.min.js";
    document.head.appendChild(s);
}
</script>
<?php
echo '<link href="style/css/leaderboards.css" rel="stylesheet"/><title>Dash & Mash ~ Leaderboards</title></head><body>';
include 'lib/commons/header.php';
include 'lib/commons/nav.php';
echo '<main class="ldrboards"><header><h1>Leaderboards</h1></header>';

if ($user_log_id) {

    $stmt_post_count = $dbconnect->prepare("SELECT post_id FROM `posts` WHERE post_author_id = ?") or die($dbconnect->error);
    $stmt_post_count->bind_param('s', $user_log_id);
    $stmt_post_count->execute();
    $result_post_count = $stmt_post_count->get_result();
    $stmt_post_count->close();
    $posts = mysqli_num_rows($result_post_count);

    $stmt_score = $dbconnect->prepare("SELECT rank_score, rank_map, rank_user FROM `rankings` WHERE rank_user = ?") or die($dbconnect->error);
    $stmt_score->bind_param('s', $user_log_id);
    $stmt_score->execute();
    $stmt_score->bind_result($score, $map, $ranking_user);
    $result_score = $stmt_score->get_result();
    $stmt_score->close();

    $stmt_rank_check = $dbconnect->prepare("SELECT * FROM `rankings` WHERE rank_user = ?") or die($dbconnect->error);
    $stmt_rank_check->bind_param('i', $user_log_id);
    $stmt_rank_check->execute();
    $result_rank_check = $stmt_rank_check->get_result();
    $stmt_rank_check->close();

    $stmt_rank = $dbconnect->prepare("SELECT COUNT(rank_user)+1 AS rank FROM `rankings` WHERE rank_score > (SELECT rank_score FROM `rankings` WHERE rank_user = ?);") or die($dbconnect->error);
    $stmt_rank->bind_param('i', $user_log_id);
    $stmt_rank->execute();
    $stmt_rank->bind_result($rank);
    $result_rank = $stmt_rank->get_result();
    $stmt_rank->close();
    

    if (mysqli_num_rows($result_rank_check) > 0) {
        while ($row_rank = $result_rank->fetch_assoc()) {
            $rank = $row_rank["rank"];
        }
    } else {
        $rank = "unranked";
    }

    if (mysqli_num_rows($result_score) > 0) {
        while ($row_score = $result_score->fetch_assoc()) {
            $score = $row_score["rank_score"];
        }
    } else {
        $score = 0;
    }

    if ($user_log_level !== 1) {
        if ($posts == 0) {
            $level = "Newbie";
        } else if ($posts > 10 && $posts < 50) {
            $level = "Known";
        } else if ($posts > 50) {
            $level = "God-tier";
        }
    } else if ($user_log_level === 1) {
        $level = "Administrator";
    } else if ($user_log_level === 10) {
        $level = "Banned";
    }

    echo "<section class='myrank'>
            <h2>Your Ranking</h2>
            <div>
                <h6>$user_log_name</h6>
                <ul>
                    <li><strong>Rank</strong>$rank</li>
                    <li><strong>Score</strong>$score</li>
                    <li><strong>Forum Posts</strong>$posts</li>
                    <li><strong>User Level</strong>$level</li>
                </ul>
            </div>
        </section> ";
}
?>
<section class="parallax">
    <header><h2>Top 10 Score Ranking</h2></header>
    <ol>
        <?php
        $ranks = json_decode(file_get_contents("https://dashmash.ddns.net/lib/game/ranks.php"), true);

        for ($i = 0; $i < count($ranks["rankings"]); $i++) {
            AddRank($ranks["rankings"][$i][0]["rank_score"], $ranks["rankings"][$i][1]["user_name"], $ranks["rankings"][$i][2]["profile_avatar"]);
        }

        function AddRank($score, $user, $avatar) {
            echo "<li><div><a href='https://dashmash.ddns.net/profile.php?id=$user'>"
                    . "<img src='https://dashmash.ddns.net/lib/user/uploads/avatar/$avatar' alt='avatar'/>"
                    . "<span>$user</span></a></div>"
                    . "<mark>$score</mark>";
        }
        ?>
    </ol>
</section>

<?php
include 'lib/commons/page-footing.php';
echo '</main>';