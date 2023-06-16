<!DOCTYPE HTML>
<?php
include 'lib/commons/connect.php';
include 'lib/commons/session.php';
?>
<html>
    <head>
        <title>Dash & Mash ~ Playing</title>
        <link href='//dashmash.ddns.net/style/css/fonts.css' rel='stylesheet'>
        <link href='//dashmash.ddns.net/style/css/game/game-css.css' rel='stylesheet'>
        <?php
        if (!$user_log_id) {
            echo "<link href='//dashmash.ddns.net/style/css/game/loginUI.css' rel='stylesheet'>";
        } else {
            echo "<script src='//dashmash.ddns.net/script/game-window-optionality.js' defer></script>";
        }
        ?>

    </head>
    <body>
        <?php
        if ($user_log_id) {

           /* echo "<dialog id='game-dialog'>";
            echo "<div class='tutorial'>";
            echo "<p data-sentence-id='10'>Jump over pits!</p>";
            echo "</div>";
            echo "</dialog>";*/
            echo "     
                    <dialog class='greet' open><p>Press <b style='color: #f90'>F</b> to enter fullscreen</p></dialog>
                    <script src = '//dashmash.ddns.net/game/lib/melonjs.js'></script>

                    <!-- Game Scripts -->
                    <script src='//dashmash.ddns.net/game/js/game.js'></script>
                    <script src='//dashmash.ddns.net/game/js/controls.js'></script>

                    <!-- Plugins 
                    <script src='//dashmash.ddns.net/game/lib/plugins/dialog.js'></script>-->
                    <script src='//dashmash.ddns.net/game/lib/plugins/debugPanel.js'></script>

                    <!-- Entities -->    
                    <script src='//dashmash.ddns.net/game/js/entities/player.js'></script>
                    <script src='//dashmash.ddns.net/game/js/entities/enemy.js'></script>
                    <script src='//dashmash.ddns.net/game/js/entities/HUD.js'></script>
                    <script src='//dashmash.ddns.net/game/js/entities/dialog.js'></script>
                    <!--<script src='//dashmash.ddns.net/game/js/entities/sword.js'></script>
                    <script src='//dashmash.ddns.net/game/js/entities/laser.js'></script>-->

                    <!-- Screens -->    
                    <script src='//dashmash.ddns.net/game/js/screens/GUI.js'></script>    
                    <script src='//dashmash.ddns.net/game/js/screens/play.js'></script>    
                    <script src='//dashmash.ddns.net/game/js/screens/loader.js'></script>
                    <script src='//dashmash.ddns.net/game/js/screens/title.js'></script>
                    <script src='//dashmash.ddns.net/game/js/screens/score.js'></script>
                    <script src='//dashmash.ddns.net/game/js/screens/select.js'></script>
                    <script src='//dashmash.ddns.net/game/js/screens/settings.js'></script>
                    <script src='//dashmash.ddns.net/game/js/screens/leaderboard.js'></script>

                    <!-- rsc -->
                    <script src='//dashmash.ddns.net/game/data/rsc/resources.js'></script>
                    

";
            
        } else {
            include "lib/game/login.php";
        }
        ?>
    </body>
</html>