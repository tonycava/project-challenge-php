<?php

use Discord\Discord;
use Discord\WebSockets\Event;
use Discord\WebSockets\Intents;

require_once('vendor/autoload.php');
require_once('utils/token.php');

$discord = "";

function launchDiscordBot(): void
{
    $key = getEnvironmentVariable('DISCORD_TOKEN');

    echo $key;

    try {
        $discord = new Discord([
            'token' => $key,
        ]);

        $discord->on('ready', function (Discord $discord) {
            echo "bot is ready";


            echo "heartbeat called at: " . time() . PHP_EOL;
        });

        $discord->run();
    } catch (\Discord\Exceptions\IntentException $e) {
    }
}

launchDiscordBot();


$client = new React\Http\Browser();

$http = new React\Http\HttpServer(function (Psr\Http\Message\ServerRequestInterface $request) {
    return React\Http\Message\Response::plaintext(
        "Hello World!\n"
    );
});

$socket = new React\Socket\SocketServer('0.0.0.0:8080');
$http->listen($socket);


function test()
{
    $key = getEnvironmentVariable('DISCORD_TOKEN');
    $discord = new Discord([
        'token' => $key,
    ]);

    $guild = $discord->guilds->get('id', '917437857243734067');
    $DiscordChannel = $guild->channels->get('id', '1027847561308016650');
    $DiscordChannel->sendMessage('message');
}