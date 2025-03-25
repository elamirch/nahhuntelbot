<?php
include_once("./modules/bootstrap.php");

//Get webhook inputs
$update = json_decode(file_get_contents('php://input'));

$user_id = $update->message->from->id ?? $update->callback_query->from->id;

//user_info variable is derived from our channel, as a result it has
//more information than the user object in $update such as a 
//username (which is exactly what we'll use for usage monitoring
$user_info = json_decode($telegram->getChatMember($user_id))->result;

//If the user sent a text (and not a callback_query)
if (isset($update->message->text)) {
    //Ask the user to join the bot's channel if not yet joined
    if ($user_info->status == "left") {
        $telegram->sendMessage($user_id, $NEW_MEMBER_MESSAGE);
    } else {
        $user_record = $user->read("telegram_user_id", $user_id);

        //Check if user exists in database
        require_once('modules/queryUser.php');

        $checkpoint = getCheckpoint($user_record);

        if($checkpoint) {
            require_once('handlers/checkpoints.php');
        } else {
            switch ($update->message->text) {
                //Start the bot
                case '/start':
                    require_once('handlers/start.php');
                    break;
                
                //Main menu buttons
                case 'Conversations 💬':
                    require_once('handlers/menuButtons/conversations.php');
                    break;
                case 'Models 🤖':
                    require_once('handlers/menuButtons/models.php');
                    break;
                case 'Manual 📖':
                    require_once('handlers/menuButtons/manual.php');
                    break;
    
                //Chatting with AI
                default:
                    require_once('handlers/chat.php');
                    break;
            }
        }

        //Send usage info to admin
        $telegram->sendMessage($ADMIN_CHAT_ID,
            $user_info->user->first_name . " is using the bot\n" .
            "Username: @" . $user_info->user->username . "\n" .
            "User ID: " . $user_id . "\n" .
            "Cost: $cost"
        );
    }
//If the user sent a callback_qeury (and not a text)
} elseif (isset($update->callback_query)) {
    $callback_data = $update->callback_query->data;
    switch ($callback_data) {        
        case 'new_session':
            require_once('handlers/callback/newSession.php');
            break;
        
        //Only conversation selection values are have no prefixes
        default:
            $callback_function = substr($callback_data, 0, 3);
            //callback_function equals the first 3 letters of the conversation if no
            //functions are set as the first letters
            assign_common_variables();            

            switch ($callback_function) {
                case 'sel':
                    require_once('handlers/callback/selectSession.php');
                    break;
                case 'ren':
                    require_once('handlers/callback/renameSession.php');
                    break;
                case 'del':
                    require_once('handlers/callback/deleteSession.php');
                    break;
                default:
                    //selected_session equals callback data when no functions are set
                    require_once('handlers/callback/sessionSettingMenu.php');
                    break;
            }
    }
}