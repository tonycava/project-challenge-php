<?php

require_once('./vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable("./");
$dotenv->load();

function telegramSendMessage(mixed $message): void
{

    $apiToken = $_ENV['TELEGRAM_API_TOKEN'];
    $data = [
        'chat_id' => $_ENV['TELEGRAM_CHAT_ID'],
        'text' => $message->comment_tittle
    ];
    file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?" .
        http_build_query($data) );
}