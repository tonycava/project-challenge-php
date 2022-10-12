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

$app->get("/", function (Request $request, Response $response) {
    $response->getBody()->write(json_encode(["successful" => "true"]));
    return $response;
});

$app->post('/new-comment', function (Request $request, Response $response) {



    $json = $request->getBody();

    $data = json_decode($json);
    telegramSendMessage();
    discordSendMessage($data);
    return $response;
});

$app->run();
