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
