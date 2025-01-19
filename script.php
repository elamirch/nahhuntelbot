<?php
include_once("./modules/bootstrap.php");

//Just a number close to the current time's last update id
$last_update_id = 380000000;
$main_bot_id = $bot->main_bot_init();

//Initiating common variables

$user_record = [];
$chat_sessions = [];
$current_session = [];
$current_session_name = '';

while (true) {
if (date('H:i') === '00:00') {
    $pdo->query('UPDATE nahhuntel.users SET credit = 10;');
}
sleep(1);
echo "Last update id: " . $last_update_id . "\n";
$updates = json_decode($telegram->getUpdates($last_update_id))->result ?? False;
if ($updates) {
foreach ($updates as $update) {
    var_dump($update);
//This is the code that runs in each cycle:
$last_update_id = $update->update_id;
$user_id = $update->message->from->id ?? $update->callback_query->from->id;

//user_info variable is derived from our channel, as a result it has
//more information than the user object in $update object such as a 
//first name (which is exactly what we'll use for usage monitoring
$user_info = json_decode($telegram->getChatMember($user_id))->result;

//If the user sent a text (and not a callback_query)
if (isset($update->message->text)) {
    //Ask the user to join the bot's channel if not yet joined
    if ($user_info->status == "left") {
        $telegram->sendMessage($user_id, $NEW_MEMBER_MESSAGE);
    } else {
        //Check if user exists in database
        $user_record = $user->read("telegram_user_id", $user_id)[0];
        var_dump($user_record);
        if ($user_record != null) {
            echo "New message\n";
        } else {
            //When a new user joins
            //If the user has no Telegram username, set it to NOT.SET in database
            $username = $user_info->user->username ?? 'NOT.SET';

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
            $user->create($username,
            $user_id, $main_bot_id, $chat_sessions,
            $current_session);
            
            echo "New user added: " . $username . ", " . $user_id . "\n";
        }

        $user_record = $user->read("telegram_user_id", $user_id)[0];
        $checkpoint = ($user_record['checkpoint'] != null) ? json_decode($user_record['checkpoint']) : false;
        if($checkpoint) {
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
        } else {
            switch ($update->message->text) {
                case '/start':
                    // on /start command
                    echo "Start message to be sent\n";
                    $telegram->sendMessage($user_id, $START_MESSAGE, $main_menu);
                    break;
                
                //Main menu buttons
                case 'Conversations 💬':
                    assign_common_variables();
    
                    //Create a menu containing all chat sessions
                    $sessions_menu_raw = [];
                    $buttons_list = [];
                    foreach ($chat_sessions as $chat_session_name => $chat_session) {
                        $new_inline_buttons = [
                            'text' => "$chat_session_name",
                            'callback_data' => "$chat_session",
                        ];
                        $buttons_list[] = $new_inline_buttons;
                    }
                    $new_session_button = [
                        'text' => "Start new session",
                        'callback_data' => "new_session",
                    ];
    
                    $sessions_menu_raw = [
                        'inline_keyboard' => [$buttons_list, [$new_session_button]]
                    ];
                    $sessions_menu = json_encode($sessions_menu_raw);
    
                    $telegram->sendMessage($user_id, "Current conversation: " . $current_session_name  .
                    "\nSelect conversation from the menu:", $sessions_menu);
                    break;
                case 'Models 🤖':
                    $telegram->sendMessage($user_id, "Currently the bot only supports gpt-4o-mini");
                    break;
                case 'Manual 📖':
                    $telegram->sendMessage($user_id, "To be later filled...");
                    break;
    
                default:
                    assign_common_variables();
                    $credit = $user_record['credit'];
    
                    if($credit > 0) {
                        echo "Session_id: " . $current_session_id;
                        
                        $telegram->sendMessage($user_id, "Wait! AI is thinking...");
    
                        $message = $chat->message($current_session_id, $update->message->text)->content;
                        
                        $message = $message . "\n\nConversation: " . $current_session_name ;
                        $telegram->sendMessage($user_id, $message);
    
                        $user->update("telegram_user_id", $user_id, "credit", $credit-1);
                    } else {
                        $telegram->sendMessage($user_id, "Your 10 question limit has been reached");
                    }

                    break;
            }
        }

        

        //Send usage info to admin
        $telegram->sendMessage($ADMIN_CHAT_ID, $user_info->user->first_name .
            " is using the bot\nUsername: @" . $user_info->user->username .
            "\nID: " . $user_id . "\nLast update id: $last_update_id");
    }
//If the user sent a callback_qeury (and not a text)
} elseif (isset($update->callback_query)) {
    $callback_data = $update->callback_query->data;
    switch ($callback_data) {        
        case 'new_session':
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
            break;
        
        //Only conversation selection values are non-predefined so they
        //are handled using the default case
        default:
            $callback_function = substr($callback_data, 0, 3);
            //callback_function equals the first 3 letters of the conversation if no
            //functions are set as the first letters
            assign_common_variables();            

            switch ($callback_function) {
                case 'sel':
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
                    break;
                case 'ren':
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
                    break;
                
                case 'del':
                    if(count($chat_sessions) < 2) {
                        $telegram->sendMessage($user_id, "This is your only conversation and cannot be deleted");
                    } else {
                        $selected_session = substr($callback_data, 3);
                        $selected_session_name = array_search($selected_session, $chat_sessions);
    
                        unset($chat_sessions[$selected_session_name]);
    
                        if($current_session_id == $selected_session) {
                            //Set current session to the last index if the session deleted was
                            //the current (active) one
    
                            $current_session_id = end($chat_sessions);
                            $current_session_name = array_search($current_session_id, $chat_sessions);
    
                            $current_session_raw = array(
                                $current_session_name => $current_session_id,
                            );
    
                            $current_session = json_encode($current_session_raw);
    
                            $user->update('telegram_user_id', $user_id, 
                            'current_session', 
                            $current_session);
                        }
                        
    
                        $chat->delete($selected_session);
    
                        $user->update('telegram_user_id', $user_id, 
                        'chat_sessions', 
                        json_encode($chat_sessions));
                        $telegram->sendMessage($user_id, "Conversation " . $selected_session_name .
                        " deleted\nCurrently selected conversation: $current_session_name");
                    }
                        break;
                default:
                    //selected_session equals callback data when no functions are set
                    $selected_session = $callback_data;
                    $selected_session_name = array_search($selected_session, $chat_sessions);

                    $session_select = [
                        'text' => "Select ✅",
                        'callback_data' => "sel" . $selected_session
                    ];
                    $session_rename = [
                        'text' => "Rename ✍🏻",
                        'callback_data' => "ren" . $selected_session
                    ];
                    $session_delete = [
                        'text' => "Delete ❌",
                        'callback_data' => "del" . $selected_session
                    ];
                    $session_menu_raw = [
                        'inline_keyboard' => [[$session_select, $session_rename, $session_delete]]
                    ];
                    $session_menu = json_encode($session_menu_raw);
                    echo $update->callback_query->data;
                    $telegram->sendMessage($user_id, "Settings for conversation: ".
                                $selected_session_name , $session_menu);
                    break;
            }
    }
}
$last_update_id++;
}
}
}
