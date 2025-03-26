<?php

class Telegram {
    public function getChatMember($user_id)
    {
        global $BOT_TOKEN;
        global $ASSOCIATED_CHANNEL;
        $url = "https://api.telegram.org/bot$BOT_TOKEN/getChatMember";
        $data = array(
            "chat_id" => "@$ASSOCIATED_CHANNEL",
            "user_id" => $user_id
        );
        $data = http_build_query($data);
        return curl($url,$data);
    }
    
    public function sendMessage($user_id, $text, $reply_markup = '') {
        global $BOT_TOKEN;

        //Refine the text
        $text = convertHashtag($text);
        $text = convertMarkdownToTelegram($text);
        $text = escapeMarkdownV2($text);

        $url = "https://api.telegram.org/bot$BOT_TOKEN/sendMessage";

        $data = array(
            "chat_id" => $user_id,
            "text" => $text,
            "parse_mode" => "MarkdownV2",
            "reply_markup" => $reply_markup
        );

        $header = array(
            'Content-Type: application/json',
        );

        $data = json_encode($data);
        return curl($url,$data, $header);
    }
    
    public function getUpdates($offset) {
        global $BOT_TOKEN;
        $url = "https://api.telegram.org/bot$BOT_TOKEN/getUpdates";
        $data = array(
            "offset" => $offset
        );
        $data = http_build_query($data);
        return curl($url,$data);
    }
}