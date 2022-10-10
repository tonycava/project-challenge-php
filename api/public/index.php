<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require_once('./vendor/autoload.php');
require_once('./utils/token.php');

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

    $headers = ['Content-Type: application/json; charset=utf-8'];
    $POST = ['username' => 'Testing BOT', 'content' => "$data->comment_tittle"];
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, getEnvironmentVariable("WEB_HOOK_URL"));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($POST));
    curl_exec($ch);

    $response->getBody()->write(json_encode(["successful" => "true"]));

    return $response;
});

$app->run();
