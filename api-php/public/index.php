<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require_once('./vendor/autoload.php');
require_once('./send-message/telegram.php');
require_once('./send-message/discord.php');

$dotenv = Dotenv\Dotenv::createImmutable("./");
$dotenv->load();

$app = AppFactory::create();

$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(true, true, true);
$app->addBodyParsingMiddleware();

$app->post("/", function (Request $request, Response $response) {
    $json = $request->getBody();
    $data = json_decode($json);

    $isSwearWord = false;
    $filename = fopen("../swearWords.json", "r");

    while (fgets($filename) !== false) {
        $line = fgets($filename);

        if (str_contains($data->comment_tittle, $line)) {
            $isSwearWord = true;
            break;
        }
    }
    fclose($filename);

    $response->getBody()->write($isSwearWord ? "swear" : "not");

    return $response;
});


$app->post('/new-comment', function (Request $request, Response $response) {
    $json = $request->getBody();
    $data = json_decode($json);

    $client = new \GuzzleHttp\Client();
    $resp = $client->post('https://api.emotion.laphant.tonycava.dev/get-emotion', [
        'verify' => false,
        \GuzzleHttp\RequestOptions::JSON => ['emotion' => $data->comment_tittle]
    ]);

    $emotionResponse = json_decode($resp->getBody());
    $emotion = $emotionResponse->emotion;

    telegramSendMessage($data, $emotion);
    discordSendMessage($data, $emotion);

    return $response;
});

$app->run();
