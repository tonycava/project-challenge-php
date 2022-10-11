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
            echo "";

            $guild = $discord->guilds->get('id', '917437857243734067');
            $DiscordChannel = $guild->channels->get('id', '1027847561308016650');
            $DiscordChannel->sendMessage('up adn running on vps');

            echo "heartbeat called at: " . time() . PHP_EOL;
        });

        $discord->run();
    } catch (\Discord\Exceptions\IntentException $e) {
    }
}

launchDiscordBot();