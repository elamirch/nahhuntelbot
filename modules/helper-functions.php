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
    // Helper function to escape special characters
    $escape = function($text) {
        $chars = ['_', '*', '[', ']', '(', ')', '~', '`', '>', '#', '+', '-', '=', '|', '{', '}', '.', '!'];
        foreach ($chars as $char) {
            $text = str_replace($char, '\\'.$char, $text);
        }
        return $text;
    };

    // Convert italic *text* to __text__ (ensure it does not match already-converted bold text)
    $markdown = preg_replace_callback('/(?<!\*)\*((?:(?!\*).)+?)\*(?!\*)/s', function($m) use ($escape) {
        return '__' . $escape($m[1]) . '__';
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
    return preg_replace('/^(#{1,6})\s+(.*)$/m', '**$2**', $text);
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