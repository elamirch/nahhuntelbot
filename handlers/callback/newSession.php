<?php
$username = $user_info->user->username ?? 'NOT.SET';

//Creating a metis compatible user array
$metis_user = array(
    "name" => $username,
    "id" => "$user_id"
);

//THE LOGIC TO SELECT A BOT HAS NOT BEEN YET PROVIDED

assign_common_variables();
$new_session_id = $chat->create(
            $user_record['default_bot'], $metis_user)->id;
$new_session_name  = substr($new_session_id, 0, 6);
$chat_sessions[$new_session_name] = $new_session_id;

$user->update('telegram_user_id', $user_id, 
            "chat_sessions", json_encode($chat_sessions));
$telegram->sendMessage($user_id, "New conversation " .
            "'$new_session_name ' created, you can change " .
            "the default conversation from the menu");