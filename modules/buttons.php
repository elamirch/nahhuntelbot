<?php

//Main menu
$sessions_button = [
    'text' => 'Conversations 💬',
];
$models_button = [
    'text' => 'Models 🤖',
];
$manual_button = [
    'text' => 'Manual 📖',
];
$main_menu_raw = [
    'keyboard' => [
        [$sessions_button], [$models_button], [$manual_button]
    ],
];
$main_menu = json_encode($main_menu_raw);


//Joined inline keyboard
$joined_button = [
    'text' => 'Joined ✅',
    'callback_data' => 'joined',
];
$joined_menu_raw= [
    'inline_keyboard' => [
        $joined_button
    ]
];
$joined_menu = json_encode($joined_menu_raw);