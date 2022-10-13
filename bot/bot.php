<?php

use Discord\Discord;

require_once('./vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable("./");
$dotenv->load();

function launchDiscordBot(): void
{
    $key = $_ENV['DISCORD_TOKEN'];;

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

                echo $message->author->username . "\n\n";
                if ($message->author->username === 'LAphant de wish') {
                    $client = new \GuzzleHttp\Client();
                    $response = $client->post('https://api.emotion.laphant.tonycava.dev/get-emotion', [
                        'verify' => false,
                        \GuzzleHttp\RequestOptions::JSON => ['emotion' => $message->content]
                    ]);
                    $emotionResponse = json_decode($response->getBody());
                    echo "end request \n\n";
                    if ($emotionResponse->emotion == ":(") {
                        $message->react('👎')->done(function () {
                            echo "";
                        });
                    } else {
                        $message->react('👍')->done(function () {
                            echo "";
                        });
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