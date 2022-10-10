<?php

use Discord\Discord;
use Discord\WebSockets\Event;
use Discord\WebSockets\Intents;

require_once('vendor/autoload.php');
require_once('utils/token.php');

$happy = ["elated", "excited", "happy", "joy"];
$fear = ["nervous", "terrified", "scared"];
$sad = ["unhappy", "sorrowful", "dejected", "depressed"];

function launchDiscordBot(): void
{
    $key = getEnvironmentVariable('DISCORD_TOKEN');
    echo $key;

    try {
        $discord = new Discord([
            'token' => $key,
            'intents' => Intents::getDefaultIntents() | Intents::MESSAGE_CONTENT
        ]);



        $discord->on('ready', function (Discord $discord) {
            echo "bot is ready";
            $discord->on('message', function ($message, $discord) {

                $contentMessage = $message->content;
                $guild = $discord->guilds->get('id', '917437857243734067');
                $DiscordChannel = $guild->channels->get('id', '1027847561308016650');

                if ($contentMessage === '!joke') {
                    $client = new \GuzzleHttp\Client();
                    $response = $client->request('GET', 'https://api.chucknorris.io/jokes/random', ['verify' => false]);
                    $joke = json_decode($response->getBody());
                    $joke = $joke->value;
                    $message->react('👎')->done(function () {
                        echo "";
                    });
                    $message->reply($joke);
                }
                if ($message->author->username === 'M4cht L3gend') {
                    $numberOfSwear = takeDataFromJson($message, './swearWords.json');
                    echo "$numberOfSwear";
                    if (ctype_upper(str_replace(' ', '', $message->content)) || $numberOfSwear > 0) {
                        $DiscordChannel->sendMessage(":thumbsdown:");
                    } else {
                        $DiscordChannel->sendMessage(":thumbsup:");
                    }
                }
            });

            echo "heartbeat called at: " . time() . PHP_EOL;
        });

        $discord->run();
    } catch (\Discord\Exceptions\IntentException $e) {
    }
}

function takeDataFromJson(\Discord\Parts\Channel\Message $message, string $filePath): int
{
    $number = 0;
    $json = file_get_contents("$filePath");
    $json_data = json_decode($json, true);
    print_r($json_data);
    echo count($json_data);
    echo "------";
    echo $message->content;
    echo "\n";
    for ($honor = 0; $honor < count($json_data); $honor++) {
        echo  "$message->content | $json_data[$honor] | " . strpos($message->content, $json_data[$honor]) . "\n";
        if (strpos($message->content, $json_data[$honor]) !== false) {
            echo "FOUND $message->content\n";
            $number++;
        }
    }
    return $number;
}

launchDiscordBot();