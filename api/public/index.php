<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require_once './vendor/autoload.php';
require_once('api/utils/token.php');

$app = AppFactory::create();

$app->addErrorMiddleware(true, true, true);

$app->get('/', function (Request $request, Response $response) {

    $headers = ['Content-Type: application/json; charset=utf-8'];
    $POST = ['username' => 'Testing BOT', 'content' => 'Testing message'];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, getEnvironmentVariable("WEB_HOOK_URL"));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($POST));

    curl_exec($ch);

    $response->getBody()->write('Hello, World!');

    return $response;
});

$app->run();
