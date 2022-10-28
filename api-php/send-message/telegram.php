<?php

require_once('./vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable("./");
$dotenv->load();

function telegramSendMessage(mixed $message, string $emotion): void
{
    if ($emotion == ":(") {
        $emotion = "And I think you don't want to see this comment";
        $text = "😡";
    }
    else {
        $emotion = "And I think you want to see this comment";
        $text = "😄";
    }

    $apiToken = $message->webhook_telegram_url;
    $data = [
        'chat_id' => $message->telegram_chat_id,
        'text' => "Comment : $message->comment_tittle\n\nGo moderate this new comment : https://laphant.tonycava.dev/wp-admin/edit-comments.php \n\nBy : $message->comment_author\n\n At : $message->comment_date \n\n $emotion"
    ];

    $res = json_decode(file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?" .
        http_build_query($data)));

//    $message_id = $res->result->message_id;
//    file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?chat_id=$message->telegram_chat_id&text=$text&reply_to_message_id=" . $message_id);
}