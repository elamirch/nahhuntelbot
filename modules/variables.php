<?php
    $NEW_MEMBER_MESSAGE = "Please join our channel to use the chatbot: \n@$ASSOCIATED_CHANNEL";
    $START_MESSAGE = "Bot started, send any message to initiate a conversation!\n\n" .
    "\n\nAnytime you needed more help on how to make the best use of the bot, use the".
    "main menu\n\nCreated with love by @$ADMIN_USER_NAME ❤️";
    
    //Assign, or reassign common variables
    function assign_common_variables() {
        global $user;
        global $user_id;
        global $user_record;
        global $chat_sessions;
        global $current_session;
        global $current_session_name;
        global $current_session_id;

        //Retrieve user chat sessions
        $user_record = $user->read("telegram_user_id", $user_id)[0];
        $chat_sessions = json_decode($user_record['chat_sessions'], true);
    
        //Retrieve current user session
        $current_session = json_decode($user_record['current_session'], true);
        $current_session_name = array_key_first($current_session);
        $current_session_id = (fn() => reset($current_session))();
    }