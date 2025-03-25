<?php
$selected_session = substr($callback_data, 3);
$selected_session_name = array_search($selected_session, $chat_sessions);

$current_session_raw = array(
    $selected_session_name  => $selected_session,
);
$current_session = json_encode($current_session_raw);
$user->update('telegram_user_id', $user_id, 
            'current_session', $current_session);
$telegram->sendMessage($user_id, "Conversation " . $selected_session_name .
            " selected");