<?php
assign_common_variables();

$credit = $user_record['credit'];

if($credit > 0) {
    
    $telegram->sendMessage($user_id, "Wait! AI is thinking...");

    //Get output from AI provider
    $provider_output = $chat->message($current_session_id, $update->message->text);
    
    //Get message
    $message = $provider_output->content;

    //Cost_raw is like 6.05E-5
    $cost_raw = $provider_output->billing->cost;
    //Adding 20% profit
    $cost = number_format($cost_raw  * 1.2, 6);

    //Create message metadata
    $meta_data = "<code><i>".
        "Conversation: $current_session_name \n" .
        "Cost: $cost \n" . 
        "</i></code>";

    //Add metadata to message
    $message = $message . "\n\n" . $meta_data;

    //Parse to HTML
    $message = markdownV2ToHtml($message);
    
    //Send message
    $telegram->sendMessage($user_id, $message);

    //Decrease credit
    $user->update("telegram_user_id", $user_id, "credit", $credit-1);
} else {
    $telegram->sendMessage($user_id, "Your 10 question limit has been reached");
}