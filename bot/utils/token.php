<?php

function getEnvironmentVariable(string $token): string
{
    include "./bot/.env";
    $dotEnv = Dotenv\Dotenv::createUnsafeImmutable('./');
    $dotEnv->load();
    return $_ENV[$token];
}