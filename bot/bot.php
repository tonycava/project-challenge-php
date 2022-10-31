<?php

use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Exceptions\IntentException;
use Discord\Parts\WebSockets\MessageReaction;
use Discord\WebSockets\Event;
use Discord\Parts\Channel\Message;
use Dotenv\Dotenv;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

require_once('./vendor/autoload.php');

$dotenv = Dotenv::createImmutable("./");
$dotenv->load();

const BOT_USERNAME = "LAphant de wish";

function launchDiscordBot(): void
{
  try {
    $discord = new Discord([
      'token' => $_ENV['DISCORD_TOKEN'],
    ]);

    $discord->on(strtolower(Event::READY), function (Discord $discord) {
      $guild = $discord->guilds->get('id', '917437857243734067');
      $discordChannel = $guild->channels->get('id', '1027847561308016650');

      $discord->on(Event::MESSAGE_REACTION_ADD, function (MessageReaction $reaction, Discord $discord) use ($discordChannel) {
        $reaction->fetch()->then(function ($done) use ($reaction, $discord, $discordChannel) {
          $cross = $reaction->message->reactions->get("id", "❌")->count == null ? 0 : $reaction->message->reactions->get("id", "❌")->count;
          $valid = $reaction->message->reactions->get("id", "✔")->count == null ? 0 : $reaction->message->reactions->get("id", "✔")->count;

          var_dump($cross);
          var_dump($valid);

          if ($cross + $valid === 3 && $done->message->author->bot && ($done->emoji->name == "❌" || $done->emoji->name == "✔")) {
            $discordChannel->getMessageHistory([
              'before' => $done->message->id,
              'limit' => 1,
            ])->done(function ($messages) use ($reaction, $done) {
              foreach ($messages as $message) {
                $array = explode(" ", $message->content);
                $last = end($array);
                $commentId = str_replace("#", "", $last);
                $link = new mysqli("wordpress_db:3306", "username", "password", "wordpress") or die("Error when connecting to database");

                if ($done->emoji->name == "❌") {
                  $link->query(/** @lang sql */ "UPDATE wp_comments SET comment_approved = \"trash\" WHERE comment_ID = $commentId")->fetch_assoc();
                  $reaction->message->edit(MessageBuilder::new()->setContent($reaction->message->content . "(Already approved or in trash)"));
                } elseif ($done->emoji->name == "✔") {
                  $link->query(/** @lang sql */ "UPDATE wp_comments SET comment_approved = 1 WHERE comment_ID LIKE $commentId")->fetch_assoc();
                  $reaction->message->edit(MessageBuilder::new()->setContent($reaction->message->content . "(Already approved or in trash)"));
                }
                mysqli_close($link);
              }
            });
          }
        });
      });

      $discord->on(Event::MESSAGE_CREATE, function (Message $message, Discord $discord) use ($discordChannel) {
        if ($message->content === '!joke') {
          $client = new Client();
          $response = $client->request('GET', 'https://api.chucknorris.io/jokes/random', ['verify' => false]);
          $joke = json_decode($response->getBody())->value;
          $message->reply($joke);
        }

        if ($message->author->username === BOT_USERNAME) {
          $client = new Client();
          $response = $client->post('https://api.emotion.laphant.tonycava.dev/get-emotion', [
            'verify' => false,
            RequestOptions::JSON => ['emotion' => $message->content]
          ]);

          $emotionResponse = json_decode($response->getBody());
          if ($emotionResponse->emotion == ":(") $message->react('👎');
          else $message->react('👍');

          $discordChannel->sendMessage("Do you approve this comment or not ?")->done(function (Message $message) {
            $message->react('✔');
            $message->react('❌');
          });
        }
      });
      echo "heartbeat called at: " . time() . PHP_EOL;
    });
    $discord->run();
  } catch (IntentException $e) {
    echo "Error while connecting to discord :" . $e->getMessage();
  }
}

launchDiscordBot();