<?php

use Discord\Discord;
use Discord\WebSockets\Event;
use Discord\WebSockets\Intents;

require_once('./vendor/autoload.php');
require_once('./src/utils/token.php');

function launchDiscordBot(): void
{
    $key = getEnvironmentVariable('DISCORD_TOKEN');

    try {
        $discord = new Discord([
            'token' => $key,
        ]);

        $discord->on('ready', function (Discord $discord) {
            echo "bot is ready";
            $guild = $discord->guilds->get('id', '917437857243734067');
            $DiscordChannel = $guild->channels->get('id', '1027847561308016650');
            $DiscordChannel->sendMessage('message');

            echo "heartbeat called at: " . time() . PHP_EOL;
        });

        $discord->run();
    } catch (\Discord\Exceptions\IntentException $e) {
    }
}

