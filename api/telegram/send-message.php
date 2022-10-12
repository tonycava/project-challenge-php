<?php

require_once('./vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable("./");
$dotenv->load();

function sendMessage(): void
{

    $apiToken = $_ENV['TELEGRAM_API_TOKEN'];
    $data = [
        'chat_id' => $_ENV['TELEGRAM_CHAT_ID'],
        'text' => 'Hello from PHP!'
    ];
    file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?" .
        http_build_query($data) );
}

sendMessage();