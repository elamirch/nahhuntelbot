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