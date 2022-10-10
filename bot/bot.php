<?php

use Discord\Discord;

require_once('bot/vendor/autoload.php');
require_once('bot/utils/token.php');

function launchDiscordBot(): void
{
    $key = getEnvironmentVariable('DISCORD_TOKEN');

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