<?php

function escapeMarkdownV2($text) {
    // List of characters that need to be escaped in Telegram Markdown V2
    $reservedCharacters = '/([*_\\[\\]()~`>#+\\-=|{}.!])/'; // Escape characters properly

    // Use preg_replace_callback to escape them
    return preg_replace_callback($reservedCharacters, function ($matches) {
        return '\\' . $matches[1];
    }, $text);
}

function convertMarkdownToTelegram($markdown) {

    //Headers
    $markdown = preg_replace('/^(#{1,6})\s+(.*)$/m', '<b>$2</b>', $markdown);

    // Convert bold **bold**
    $markdown = preg_replace('/\*\*(.*?)\*\*/', '<b>\1</b>', $markdown);

    // Convert italic *text*
    $markdown = preg_replace('/(?<!\*)\*((?:(?!\*).)+?)\*(?!\*)/s', '<i>\1</i>', $markdown);

    // Convert multiline code block: ```code``` becomes <pre><code>code</code></pre>
    $markdown = preg_replace('/```(.*?)```/s', '<pre><code>\1</code></pre>', $markdown);
    
    // Convert inline code: `code` becomes <code>code</code>
    $markdown = preg_replace('/`([^`]+)`/', '<code>\1</code>', $markdown);

    // Convert links ([Link](url))
    $markdown = preg_replace('/\[(.*?)\]\((.*?)\)/', '<a href="\2">\1</a>', $markdown);

    // Convert blockquotes (lines starting with >)
    $markdown = preg_replace('/^\s*>\s*(.*)$/m', '<blockquote>\1</blockquote>', $markdown);

    return $markdown;
}

function logMessage($message) {
    $logFile = "/tmp/nahhuntel-logs.log";
    $formattedMessage = "[" . date("Y-m-d H:i:s") . "] " . print_r($message, true) . "\n";
    file_put_contents($logFile, $formattedMessage, FILE_APPEND);
}

function getCheckpoint(array $user_record): mixed {
    return !empty($user_record['checkpoint']) ? json_decode($user_record['checkpoint']) : false;
}

function removeDoubleBackslashes($text) {
    return preg_replace('/\\\\(?!["\\\\\/bfnrt])/', '', $text);
}