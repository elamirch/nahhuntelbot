<?php
$selected_session = substr($callback_data, 3);
$selected_session_name = array_search($selected_session, $chat_sessions);

$checkpoint = array(
    "type" => "conversation_rename",
    "data" => array(
        "session_name" => $selected_session_name
    )
    );

$user->update('telegram_user_id', $user_id, 
            'checkpoint', json_encode($checkpoint));
$telegram->sendMessage($user_id, "What will the new name for this conversation be?");