<?php

if ($user_record != null) {
    logMessage("New message from " . $user_record->name . 
        " with id: " . $user_record->telegram_user_id);
} else {
    //When a new user joins
    //If the user has no Telegram username, set it to not_set in database
    $username = $user_info->user->username ?? 'not_set';

    //Creating a metis compatible user array
    $metis_user = array(
        "name" => $username,
        "id" => "$user_id"
    );

    //Creating a Primary chat/session/conversation for the user
    $current_session_id = $chat->create($main_bot_id, $metis_user)->id;

    //Initializing chat_sessions variable and adding the first session
    $chat_sessions = ["Primary" => $current_session_id];

    //Add user to database
    $current_session = json_encode(array(
        "Primary" => $current_session_id
    ));
    
    $user->create(
        $username,
        $user_id,
        $main_bot_id,
        $chat_sessions,
        $current_session
    );
}