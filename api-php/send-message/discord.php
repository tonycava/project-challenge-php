<?php

function discordSendMessage(mixed $data): void
{

    $POST = "{
        \"username\": \"LAphant de wish\",
        \"content\": \"$data->comment_tittle\",
        \"embeds\": [
                {
                    \"title\": \"LAphant\",
                    \"url\": \"https://laphant.tonycava.dev\",
                    \"color\": #5814783,
                    \"fields\": [
                {
        \"name\": \"\nCreated at :\",
        \"value\": \"My date lol\"
        }
      ]
    }
  ],
  \"attachments\": []
}";

    $headers = ['Content-Type: application/json; charset=utf-8'];
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $_ENV['WEB_HOOK_URL']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($POST));
    curl_exec($ch);
}
