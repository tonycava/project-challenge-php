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
        $cross = $reaction->message->reactions->get("id", "❌")->count ?: 0;
        $valid = $reaction->message->reactions->get("id", "✔")->count ?: 0;

        if ($cross + $valid === 3 && $reaction->message->author->bot && ($reaction->emoji->name == "❌" || $reaction->emoji->name == "✔")) {
          $discordChannel->getMessageHistory([
            'before' => $reaction->message_id,
            'limit' => 1,
          ])->done(function (Message $messages) use ($reaction) {
            foreach ($messages as $message) {
              $idAtLastIndex = explode(" ", $message->content);
              $commentId = str_replace("#", "", end($idAtLastIndex));
              $link = new mysqli("wordpress_db:3306", "username", "password", "wordpress") or die("Error when connecting to database");

              if ($reaction->emoji->name == "❌") {
                $link->query(/** @lang sql */ "UPDATE wp_comments SET comment_approved = \"trash\" WHERE comment_ID LIKE $commentId");
                if (!str_contains($reaction->message->content, "(Already approved or in trash)"))
                  $reaction->message->edit(MessageBuilder::new()->setContent($reaction->message->content . "(Already approved or in trash)"));
              } elseif ($reaction->emoji->name == "✔") {
                $link->query(/** @lang sql */ "UPDATE wp_comments SET comment_approved = 1 WHERE comment_ID LIKE $commentId");
                if (!str_contains($reaction->message->content, "(Already approved or in trash)"))
                  $reaction->message->edit(MessageBuilder::new()->setContent($reaction->message->content . "(Already approved or in trash)"));
              }
              mysqli_close($link);
            }
          });
        }
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

          $discordChannel
            ->sendMessage("Do you approve this comment or not ?")
            ->done(function (Message $message) {
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