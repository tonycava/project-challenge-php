<?php

require_once('./vendor/autoload.php');

function discordSendMessage(mixed $data): void
{
    $client = new \GuzzleHttp\Client();
    $response = $client->post('https://api.emotion.laphant.tonycava.dev/get-emotion', [
        'verify' => false,
        \GuzzleHttp\RequestOptions::JSON => ['emotion' => $data->comment_tittle]
    ]);
    $emotionResponse = json_decode($response->getBody());

    if ($emotionResponse->emotion == ":(") {
        $color = "16711680";
    }else{
        $color = "65290";
    }

    $POST = "{
 \"content\": \"$data->comment_tittle\",
 \"username\": \"LAphant de wish\",
  \"embeds\": [
    {
      \"title\": \"Go moderate this comment !\",
      \"url\": \"https://laphant.tonycava.dev/wp-admin/edit-comments.php\",
      \"color\": $color,
      \"fields\": [
        {
        \"name\": \"By : $data->comment_author\",
        \"value\": \"At : $data->comment_date_gmt\"
        }]
}],
\"attachments\": []
}";

    $headers = ['Content-Type: application/json; charset=utf-8'];
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $data->webhook_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(json_decode($POST, true)));
    curl_exec($ch);
}
