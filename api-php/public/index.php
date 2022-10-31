<?php

use Dotenv\Dotenv;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require_once('./vendor/autoload.php');
require_once('./send-message/telegram.php');
require_once('./send-message/discord.php');

$dotenv = Dotenv::createImmutable("./");
$dotenv->load();

$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(true, true, true);
$app->addBodyParsingMiddleware();

$app->post('/new-comment', function (Request $request, Response $response) {
    $body = json_decode($request->getBody());

    $client = new Client();
    $resp = $client->post('https://api.emotion.laphant.tonycava.dev/get-emotion', [
        'verify' => false,
        RequestOptions::JSON => ['emotion' => $body->comment_tittle]
    ]);
    $emotionResponse = json_decode($resp->getBody());
    $emotion = $emotionResponse->emotion;

    if ($body->is_send_on_telegram == "active") telegramSendMessage($body, $emotion);
    if ($body->is_send_on_discord == "active") discordSendMessage($body, $emotion);

    return $response;
});

$app->run();
