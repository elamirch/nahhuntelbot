<?php

function escapeMarkdownV2($text) {
    // List of reserved characters that need to be escaped in markdown v2
    $reservedCharacters = [
        '*', '_', '[', ']', '(', ')', '~', '>', '#', '+', '-', '=', '|', '{', '}', '.', '!'
    ];

    // Loop through the reserved characters and escape them
    foreach ($reservedCharacters as $char) {
        $text = str_replace($char, '\\' . $char, $text);
    }

    return $text;
}

function convertMarkdownToTelegram($markdown) {
    // Helper function to escape special characters
    $escape = function($text) {
        $chars = ['_', '*', '[', ']', '(', ')', '~', '`', '>', '#', '+', '-', '=', '|', '{', '}', '.', '!'];
        foreach ($chars as $char) {
            $text = str_replace($char, '\\'.$char, $text);
        }
        return $text;
    };

    // Convert bold **text** to *text*
    $markdown = preg_replace_callback('/\*\*(.+?)\*\*/s', function($m) use ($escape) {
        return '*' . $escape($m[1]) . '*';
    }, $markdown);

    // Convert italic *text* to _text_
    $markdown = preg_replace_callback('/\*((?:(?!\*).)+?)\*/s', function($m) use ($escape) {
        return '_' . $escape($m[1]) . '_';
    }, $markdown);

    // Handle inline code `code`
    $markdown = preg_replace_callback('/`([^`]*)`/', function($m) {
        return '`' . str_replace(['`', '\\'], ['\\`', '\\\\'], $m[1]) . '`';
    }, $markdown);

    // Convert links [text](url)
    $markdown = preg_replace_callback('/\[([^\]]*)\]\(([^\)]*)\)/', function($m) use ($escape) {
        return '[' . $escape($m[1]) . '](' . $escape($m[2]) . ')';
    }, $markdown);

    // Convert blockquotes (multi-line)
    $markdown = preg_replace_callback('/(^>.*$)/m', function($m) use ($escape) {
        return $escape($m[1]);
    }, $markdown);

    return $markdown;
}

function convertHashtag($text) {
    return preg_replace('/^(#{1,6})\s+(.*)$/m', '*$2*', $text);
}

function logMessage($message) {
    $logFile = "/tmp/nahhuntel-logs.log";
    $formattedMessage = "[" . date("Y-m-d H:i:s") . "] " . print_r($message, true) . "\n";
    file_put_contents($logFile, $formattedMessage, FILE_APPEND);
}

function getCheckpoint(array $user_record): mixed {
    return !empty($user_record['checkpoint']) ? json_decode($user_record['checkpoint']) : false;
}