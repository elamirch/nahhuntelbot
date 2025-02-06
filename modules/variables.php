<?php
    $NEW_MEMBER_MESSAGE = "Please join our channel to use the chatbot: \n@$ASSOCIATED_CHANNEL\nRestart the bot after joining...";
    $START_MESSAGE = "Bot started, send any message to initiate a conversation!\n\n" .
    "\n\nAnytime you needed more help on how to make the best use of the bot, use the".
    "main menu\n\nCreated with love by @$ADMIN_USER_NAME ❤️";
    $MANUAL_MESSAGE = "Manual: How to Use Nahhuntel AI Chatbot 🤖✨

Welcome to the AI Chatbot! Follow this guide to get started and make the most out of your conversations
    
🚀 Getting Started:
🔻 Open the bot and start chatting
🔻 The bot remembers everything you tell it during a conversation, so feel free to share details and ask follow-up questions
    
💬 Manage Conversations:
Go to the Conversations Menu to:
▫️ View your previous chats 🕒
▫️ Start a new conversation ✨
▫️ Delete old chats 🗑
    
⚡️Message Limit:
🔸 You can send up to 10 messages per day
🔸 The limit resets every day at 00:00 Berlin time 🕛
    
🛠 Tips for Best Use:
🔹 Keep your questions clear and simple for the best answers 📝
🔹 If you start a new topic, create a new conversation for better organization.
    
    
🎉 Enjoy using the bot and exploring its AI-powered responses!";
    
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