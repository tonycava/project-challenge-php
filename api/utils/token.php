<?php
include_once("./.env");

function getEnvironmentVariable(string $token): string
{
    $dotEnv = Dotenv\Dotenv::createUnsafeImmutable('./');
    $dotEnv->load();
    return $_ENV[$token];
}