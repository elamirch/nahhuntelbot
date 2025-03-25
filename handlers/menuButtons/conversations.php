<?php
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

$telegram->sendMessage($user_id, 
    "Current conversation: " . $current_session_name  . "\n" .
    "Select conversation from the menu: ", $sessions_menu);