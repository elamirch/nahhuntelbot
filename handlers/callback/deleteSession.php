<?php

if(count($chat_sessions) < 2) {
    $telegram->sendMessage($user_id, "This is your only conversation and cannot be deleted");
} else {
    $selected_session = substr($callback_data, 3);
    $selected_session_name = array_search($selected_session, $chat_sessions);

    logMessage("Trying to delete $selected_session_name: $selected_session");
    logMessage("Current chat sessions: " . json_encode($chat_sessions));

    unset($chat_sessions[$selected_session_name]);

    if($current_session_id == $selected_session) {
        //Set current session to the last index if the session deleted was
        //the current (active) one

        $current_session_id = end($chat_sessions);
        $current_session_name = array_search($current_session_id, $chat_sessions);

        logMessage("Current session: $current_session_name: $current_session_id");

        $current_session_raw = array(
            $current_session_name => $current_session_id,
        );

        $current_session = json_encode($current_session_raw);

        logMessage("Current session json: " . json_encode($current_session));

        $user->update('telegram_user_id', $user_id, 
        'current_session', $current_session);
    }
    

    $chat->delete($selected_session);

    $user->update('telegram_user_id', $user_id, 
    'chat_sessions', json_encode($chat_sessions));

    $telegram->sendMessage($user_id, "Conversation " . $selected_session_name .
    " deleted\nCurrently selected conversation: $current_session_name");
}