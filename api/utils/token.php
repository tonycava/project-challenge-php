<?php
function getEnvironmentVariable(string $token): string
{
    require_once('./.env');
    $dotEnv = Dotenv\Dotenv::createUnsafeImmutable("./");
    $dotEnv->load();
    return $_SERVER[$token];
}