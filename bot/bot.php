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

                if ($message->author->username === 'LAphant de wish') {
                    $headers = ['Content-Type: application/json; charset=utf-8'];
                    $ch = curl_init();

                    curl_setopt($ch, CURLOPT_URL, "https://api.emotion.laphant.tonycava.dev/get-emotion");
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    echo $message->content . "\n";
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["emotion" => $message->content]));
                    $response = curl_exec($ch);
                    curl_close($ch);
                    echo json_decode($response)['emotion'] . "\n\n";
                    echo json_decode($response, true)['emotion'] . "\n\n";
                    if (json_decode($response, true) == ":(") {
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