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

            $discord->on("MESSAGE_REACTION_ADD", function ($reaction, Discord $discord) {
                echo "\n\n";
//
//                var_dump($reaction->me);
//                var_dump($reaction->message->content);
//
//
//                echo "\n\n";
//
//                echo "\n\n";

                $reaction->fetch()->done(function ($done) {
                    var_dump($done->emoji->name);
                    var_dump($done->emoji->id);
                    var_dump($done->emoji->guild->name);
                    var_dump($done->message->content);
                    var_dump($done->message->author->bot);
                });

                echo "\n\n";

            });

            $discord->on("message", function ($message, $discord) {
                $contentMessage = $message->content;

                $guild = $discord->guilds->get('id', '917437857243734067');
                if ($contentMessage === '!joke') {
                    $client = new \GuzzleHttp\Client();
                    $response = $client->request('GET', 'https://api.chucknorris.io/jokes/random', ['verify' => false]);
                    $joke = json_decode($response->getBody());
                    $joke = $joke->value;
                    $message->reply($joke);
                }

                if ($message->author->username === 'LAphant de wish') {
                    $client = new \GuzzleHttp\Client();
                    $response = $client->post('https://api.emotion.laphant.tonycava.dev/get-emotion', [
                        'verify' => false,
                        \GuzzleHttp\RequestOptions::JSON => ['emotion' => $message->content]
                    ]);

                    $emotionResponse = json_decode($response->getBody());
                    if ($emotionResponse->emotion == ":(") $message->react('👎');
                    else $message->react('👍');
                }
            });
            echo "heartbeat called at: " . time() . PHP_EOL;
        });
        $discord->run();
    } catch (\Discord\Exceptions\IntentException $e) {
    }
}

launchDiscordBot();