<?php

require_once('./utils/token.php');

function sendMessage(): void
{
    $apiToken = getEnvironmentVariable("TELEGRAM_API_TOKEN");
    $data = [
        'chat_id' => getEnvironmentVariable("TELEGRAM_CHAT_ID"),
        'text' => 'Hello from PHP!'
    ];
    file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?" .
        http_build_query($data) );
}

sendMessage();