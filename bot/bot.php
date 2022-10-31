<?php

use Discord\Builders\MessageBuilder;
use Discord\Discord;

require_once('./vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable("./");
$dotenv->load();

const BOT_USERNAME = "LAphant de wish";
const APPROVED = "1";
const TRASH = "trash";

function launchDiscordBot(): void
{

  try {
    $discord = new Discord([
      'token' => $_ENV['DISCORD_TOKEN'],
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
            ])->done(function ($messages) use ($reaction, $done, $discord) {
              foreach ($messages as $message) {
                $array = explode(" ", $message->content);
                $last = end($array);
                $commentId = str_replace("#", "", $last);

                $link = new mysqli("wordpress_db:3306", "username", "password", "wordpress");
                if (!$link) {
                  echo "Error: Unable to connect to MySQL." . PHP_EOL;
                  echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
                  echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
                  exit;
                }

                $res = $link->query("SELECT comment_approved FROM wp_comments WHERE comment_ID LIKE $commentId")->fetch_assoc();
                if ($res["comment_approved"] == APPROVED || $res["comment_approved"] == TRASH) {
                  $reaction->channel->editMessage($reaction->message, MessageBuilder::new()->setContent("Do you approve this comment or not ? (Already approved or in trash)"));
                };
                if ($done->emoji->name == "❌") {
                  $link->query("UPDATE wp_comments SET comment_approved = trash WHERE comment_ID LIKE $commentId")->fetch_assoc();
                } else if ($done->emoji->name == "✔") {
                  $link->query("UPDATE wp_comments SET comment_approved = 1 WHERE comment_ID LIKE $commentId")->fetch_assoc();
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