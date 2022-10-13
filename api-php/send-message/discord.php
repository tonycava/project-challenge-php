<?php

function discordSendMessage(mixed $data): void
{
    $color = "";

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
      \"title\": \"LAphant\",
      \"url\": \"https://laphant.tonycava.dev\",
      \"color\": $color,
      \"fields\": [
        {
        \"name\": \"Other features\",
        \"value\": \"Discohook can also grab images from profile pictures or emoji, manage your webhooks, and more. Invite the bot and use **/help** to learn about all the bot offers!\"
        }]
}],
\"attachments\": []
}";

    $headers = ['Content-Type: application/json; charset=utf-8'];
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $_ENV['WEB_HOOK_URL']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(json_decode($POST, true)));
    curl_exec($ch);
}
