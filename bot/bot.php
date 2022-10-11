<?php

use Discord\Discord;

require_once('./vendor/autoload.php');
require_once('./utils/token.php');

function launchDiscordBot(): void
{
    $key = getEnvironmentVariable('DISCORD_TOKEN');

    try {
        $discord = new Discord([
            'token' => $key,
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
                    $message->reply($joke);
                }
                if ($message->author->username === 'Testing BOT') {
                    if (ctype_upper($message->content)) {
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

launchDiscordBot();