<?php

include "./.env";

function getEnvironmentVariable(string $token): string
{
    $dotEnv = Dotenv\Dotenv::createUnsafeImmutable('./bot/utils');
    $dotEnv->load();
    return $_ENV[$token];

}