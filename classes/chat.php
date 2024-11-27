<?php

class Chat {
    public function create(string $bot_id, array $user, array $initialMessages = null) {
        $url = "https://api.metisai.ir/api/v1/chat/session";

        //Encoding initialMessages if existed
        $encoded_initialMessages = [];
        if(!is_null($initialMessages)){
            foreach ($initialMessages as $message) {
                $encoded_initialMessages = [
                    "type" => "USER",
                    "content" => $message
                ];
            }
        }

        //Creating data variable
        $data = array(
            "botId" => $bot_id,
            "user" => $user,
            "initialMessages" => $encoded_initialMessages
        );
        $data = json_encode($data);

        //Creating header
        global $MODEL_PROVIDER_API_KEY;
        $header = array(
            "Authorization: Bearer $MODEL_PROVIDER_API_KEY",
            'Content-Type: application/json',
        );

        //Returning the output of curl
        return json_decode(curl($url, $data, $header));
    }

    public function message(string $session_id, string $message) {
        $url = "https://api.metisai.ir/api/v1/chat/session/$session_id/message";

        //Creating data variable
        $data = array(
            "message" => array(
                "content" => $message,
                "type" => "USER"
            )
        );
        $data = json_encode($data);

        //Creating header
        global $MODEL_PROVIDER_API_KEY;
        $header = array(
            "Authorization: Bearer $MODEL_PROVIDER_API_KEY",
            'Content-Type: application/json',
        );

        //Returning the output of curl
        return json_decode(curl($url, $data, $header));
    }

    public function delete(string $session_id) {
        $url = "https://api.metisai.ir/api/v1/chat/session/$session_id";

        //Creating header
        global $MODEL_PROVIDER_API_KEY;
        $header = array(
            "Authorization: Bearer $MODEL_PROVIDER_API_KEY",
            'Content-Type: application/json',
        );

        //Returning the output of curl
        return json_decode(curl($url, header: $header, request: "DELETE"));
    }
}