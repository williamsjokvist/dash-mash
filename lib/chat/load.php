<?php

include '../commons/connect.php';
include '../commons/session.php';

$latest = filter_input(INPUT_GET, "l", FILTER_SANITIZE_NUMBER_INT);

/* Fetch msg stuff, username, avatar */
$stmt_chat = $dbconnect->prepare("SELECT chat.chat_id, chat.chat_user_id, chat.chat_content, chat.chat_date, `user-accounts`.user_name, `user-profile`.profile_avatar FROM chat" .
        " INNER JOIN `user-accounts` ON chat.chat_user_id = `user-accounts`.user_id" .
        " INNER JOIN `user-profile` ON chat.chat_user_id = `user-profile`.profile_user_id") or die($dbconnect->error);

$stmt_chat->execute();
$stmt_chat->bind_result($chat_id, $chat_user, $chat_content, $chat_date, $user_name, $user_avatar);
$res_chat = $stmt_chat->get_result();

if (mysqli_num_rows($res_chat) > 0) {
    while ($row_chat = $res_chat->fetch_assoc()) {
        $chat_id = $row_chat["chat_id"];
        $chat_user = $row_chat["chat_user_id"];
        $chat_content = $row_chat["chat_content"];
        $chat_date =  new DateTime($row_chat["chat_date"]);
        $chat_name = $row_chat["user_name"];
        $user_avatar = $row_chat["profile_avatar"];

        if ($chat_id > $latest) {
            echo "<li data-msg='$chat_id'><article><img src='//dashmash.ddns.net/lib/user/uploads/avatar/$user_avatar' alt='avatar'/><a href='//dashmash.ddns.net/profile.php?id=$chat_name' target='_blank'>$chat_name</a><time>" . $chat_date->format('H:i') . "</time><p>$chat_content</p></article></li>";
        }
    }
}

$stmt_chat->close();
$dbconnect->close();