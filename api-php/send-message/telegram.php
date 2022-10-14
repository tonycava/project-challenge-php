<?php

require_once('./vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable("./");
$dotenv->load();

function telegramSendMessage(mixed $message): void
{

    $apiToken = $_ENV['TELEGRAM_API_TOKEN'];
    $data = [
        'chat_id' => $_ENV['TELEGRAM_CHAT_ID'],
        'text' => "Comment : $message->comment_tittle\n\nGo moderate this new comment : https://laphant.tonycava.dev/wp-admin/edit-comments.php By : $message->comment_author\n\n At : $message->comment_date"
    ];
    file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?" .
        http_build_query($data));
}