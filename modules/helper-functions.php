<?php
function markdownV2ToHtml($text) {
    // Convert **bold** to <b>bold</b>
    $text = preg_replace('/\*\*(.*?)\*\*/s', '<b>$1</b>', $text);

    // Convert *italic* to <i>italic</i>
    $text = preg_replace('/\*(.*?)\*/s', '<i>$1</i>', $text);

    // Convert __underline__ to <u>underline</u>
    $text = preg_replace('/__(.*?)__/s', '<u>$1</u>', $text);

    // Convert ~~strikethrough~~ to <s>strikethrough</s>
    $text = preg_replace('/~~(.*?)~~/s', '<s>$1</s>', $text);

    // Convert `inline code` to <code>inline code</code>
    $text = preg_replace('/`([^`]+)`/s', '<code>$1</code>', $text);

    // Convert ```multiline code``` to <pre><code>multiline code</code></pre>
    $text = preg_replace('/```(.*?)```/s', '<pre><code>$1</code></pre>', $text);

    // Convert [text](URL) to <a href="URL">text</a>
    $text = preg_replace('/\[(.*?)\]\((.*?)\)/s', '<a href="$2">$1</a>', $text);

    // Convert PHP doc comments (e.g., /** */) to <pre><code>PHP doc comment</code></pre>
    $text = preg_replace('/\/\*\*(.*?)\*\//s', '<pre><code>$1</code></pre>', $text);

    // Convert markdown headings (e.g., # Heading, ## Heading, ### Heading) to <b>Heading</b>
    $text = preg_replace('/^(#{1,6})\s+(.*)$/m', '<b>$2</b>', $text); // Convert all headings to bold

    // Convert HTML-like tags <code> to <code> HTML tags in the example
    $text = preg_replace('/&lt;(.*?)&gt;/s', '<code>&lt;$1&gt;</code>', $text);

    // Ensure <code> blocks are wrapped properly in <pre><code> for PHP examples
    $text = preg_replace('/<code>(.*?)<\/code>/s', '<pre><code>$1</code></pre>', $text);

    return $text;
}

function logMessage($message) {
    $logFile = "/tmp/nahhuntel-logs.log";
    $formattedMessage = "[" . date("Y-m-d H:i:s") . "] " . print_r($message, true) . "\n";
    file_put_contents($logFile, $formattedMessage, FILE_APPEND);
}

function getCheckpoint(array $user_record): mixed {
    return !empty($user_record['checkpoint']) ? json_decode($user_record['checkpoint']) : false;
}