<?php

switch ($checkpoint->type) {
    case 'conversation_rename':
        assign_common_variables();

        $new_session_name = $update->message->text;
        
        //This selected_session_name variable refers to the session selected in the database,
        //not the callback function
        $selected_session_name = $checkpoint->data->session_name;

        $chat_sessions[$new_session_name] = $chat_sessions[$selected_session_name];
        unset($chat_sessions[$selected_session_name]);

        if($current_session_name == $selected_session_name) {
            $current_session[$new_session_name] = end($current_session);
            unset($current_session[$selected_session_name]);

            $user->update("telegram_user_id", $user_id, "current_session",
                    json_encode($current_session));
        }

        $chat_sessions = json_encode($chat_sessions);
        $user->update("telegram_user_id", $user_id, "chat_sessions",
                    $chat_sessions);
        $user->update("telegram_user_id", $user_id, "checkpoint",
                    null);

        $telegram->sendMessage($user_id, "Conversation name changed from ".
                    "$selected_session_name to $new_session_name");
        break;
}