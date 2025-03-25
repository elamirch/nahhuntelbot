<?php
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