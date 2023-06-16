<chat-window>
    <script src='//dashmash.ddns.net/lib/chat/chat.js' defer></script>
    <header><h3>Chat</h3></header>
    <ol><li data-msg="0"><p  style="color:#999;">Welcome to the chat</p></li></ol>
    <footer>
        <?php
        /* Delete posts older than 5 min */
        $stmt_update = $dbconnect->prepare("DELETE FROM chat WHERE chat_date < NOW() - INTERVAL 5 MINUTE");
        $stmt_update->execute();
        $stmt_update->close();

        if (isset($_SESSION['logged_in']) && $user_log_id) {
            echo "<textarea placeholder='Your Message' name='content'></textarea><button type='submit'></button>";
        } else {
            echo "<p>Log in to join the chat!</p>";
        }
        ?>
    </footer>
</chat-window>