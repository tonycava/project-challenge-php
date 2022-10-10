<?php

function getEnvironmentVariable(string $token): string
{
    include "./.env";
    $dotEnv = Dotenv\Dotenv::createUnsafeImmutable('./');
    $dotEnv->load();
    return $_ENV[$token];
}