<?php

require_once('./vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable("./");
$dotenv->load();

function telegramSendMessage(mixed $message, string $emotion): void
{
    if ($emotion == ":(") $emotion = "And I think this message is pretty bad";
    else $emotion = "And I think this message is pretty cool";


    $apiToken = $_ENV['TELEGRAM_API_TOKEN'];
    $data = [
        'chat_id' => $_ENV['TELEGRAM_CHAT_ID'],
        'text' => "Comment : $message->comment_tittle\n\nGo moderate this new comment : https://laphant.tonycava.dev/wp-admin/edit-comments.php \n\nBy : $message->comment_author\n\n At : $message->comment_date \n\n $emotion"
    ];
    file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?" .
        http_build_query($data));
}