<?php
include_once(".env");

function getEnvironmentVariable(string $token): string
{
    $dotEnv = Dotenv\Dotenv::createUnsafeImmutable('./');
    $dotEnv->load();
    echo $_ENV[$token];
    return $_ENV[$token];
}