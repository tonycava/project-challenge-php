<?php

$headers = ['Content-Type: application/form-data; charset=utf-8'];

$POST = ['comment_tittle' => 'Testing BOT'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://localhost:8080");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$data = array(
    'comment_tittle' => 'Testing BOT'
);

curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$res = curl_exec($ch);

print_r($res);