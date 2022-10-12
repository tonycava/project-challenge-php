<?php

function discordSendMessage(mixed $data) {
    $headers = ['Content-Type: application/json; charset=utf-8'];
    $POST = ['username' => 'Testing BOT', 'content' => "$data->comment_tittle"];
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $_ENV['WEB_HOOK_URL']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($POST));
    curl_exec($ch);
}
