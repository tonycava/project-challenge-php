<?php

use Discord\Discord;

require_once('./vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable("./");
$dotenv->load();

const BOT_USERNAME = "LAphant de wish";

function launchDiscordBot(): void
{
  $key = $_ENV['DISCORD_TOKEN'];;

  try {
    $discord = new Discord([
      'token' => $key,
    ]);

    $discord->on('ready', function (Discord $discord) {

      $discord->on("MESSAGE_REACTION_ADD", function (\Discord\Parts\WebSockets\MessageReaction $reaction, Discord $discord) {
        echo "\n\n";
        $guild = $discord->guilds->get('id', '917437857243734067');
        $discordChannel = $guild->channels->get('id', '1027847561308016650');

        $reaction->fetch()->then(function ($done) use ($reaction, $discord, $discordChannel) {
          $cross = $reaction->message->reactions->get("id", "❌")->count == null ? 0 : $reaction->message->reactions->get("id", "❌")->count;
          $valid = $reaction->message->reactions->get("id", "✔")->count == null ? 0 : $reaction->message->reactions->get("id", "✔")->count;
          var_dump($cross);
          var_dump($valid);

          if ($cross + $valid === 3 && $done->message->author->bot && ($done->emoji->name == "❌" || $done->emoji->name == "✔")) {
            $discordChannel->getMessageHistory([
              'before' => $done->message->id,
              'limit' => 1,
            ])->done(function ($messages) {

              foreach ($messages as $message) {
                $array = explode(" ", $message->content);
                $last = end($array);
                $commentId = str_replace("#", "", $last);

                $link = new mysqli("wordpress_db:8069", "username", "password", "wordpress");
                $res = $link->query("SELECT * FROM wp_comments");
                while ($row = $res->fetch_assoc()) {
                  echo $row["comment_content"] . "\n";
                }

                if (!$link) {
                  echo "Error: Unable to connect to MySQL." . PHP_EOL;
                  echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
                  echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
                  exit;
                }

                echo "Success: A proper connection to MySQL was made!" . PHP_EOL;
                echo "Host information: " . mysqli_get_host_info($link) . PHP_EOL;
                mysqli_close($link);
              }
            });
          }
        });
        echo "\n\n";
      });

      $discord->on("message", function ($message, Discord $discord) {
        $contentMessage = $message->content;
        $guild = $discord->guilds->get('id', '917437857243734067');
        if ($contentMessage === '!joke') {
          $client = new \GuzzleHttp\Client();
          $response = $client->request('GET', 'https://api.chucknorris.io/jokes/random', ['verify' => false]);
          $joke = json_decode($response->getBody());
          $joke = $joke->value;
          $message->reply($joke);
        }

        if ($message->author->username === BOT_USERNAME) {
          $client = new \GuzzleHttp\Client();
          $response = $client->post('https://api.emotion.laphant.tonycava.dev/get-emotion', [
            'verify' => false,
            \GuzzleHttp\RequestOptions::JSON => ['emotion' => $message->content]
          ]);

          $emotionResponse = json_decode($response->getBody());
          if ($emotionResponse->emotion == ":(") $message->react('👎');
          else $message->react('👍');

          $guild = $discord->guilds->get('id', '917437857243734067');
          $discordChannel = $guild->channels->get('id', '1027847561308016650');
          $discordChannel->sendMessage("Do you approve this comment or not ?")->done(function (\Discord\Parts\Channel\Message $message) {
            $message->react('✔');
            $message->react('❌');
          });
        }
      });
      echo "heartbeat called at: " . time() . PHP_EOL;
    });
    $discord->run();
  } catch (\Discord\Exceptions\IntentException $e) {
  }
}

launchDiscordBot();