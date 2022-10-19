<?php

require_once('./vendor/autoload.php');

function discordSendMessage(mixed $data, string $emotion): void
{
    $isSwearWord = false;
    $string = file_get_contents("swearWords.json");
    $json_a = json_decode($string, true);
    foreach ($json_a["swearWords"] as $item) {
        if (str_contains($data->comment_tittle,$item)) {
            $isSwearWord = true;
        }
    }

    if ($emotion == ":(") $color = "16711680";
    elseif ($isSwearWord) $color = "null";
    else $color = "65290";

    $POST = "{
 \"content\": \"Comment : $data->comment_tittle\",
 \"username\": \"LAphant de wish\",
  \"embeds\": [
    {
      \"title\": \"Go moderate this comment !\",
      \"url\": \"https://laphant.tonycava.dev/wp-admin/edit-comments.php\",
      \"color\": $color,
      \"fields\": [
        {
        \"name\": \"By : $data->comment_author\",
        \"value\": \"At : $data->comment_date\"
        }]
}],
\"attachments\": []
}";

    $headers = ['Content-Type: application/json; charset=utf-8'];
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $data->webhook_discord_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(json_decode($POST, true)));
    curl_exec($ch);
}
