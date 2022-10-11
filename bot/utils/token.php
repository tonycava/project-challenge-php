<?php

function getEnvironmentVariable(string $token): string
{
    try {
        include "./.env";
    } catch (\mysql_xdevapi\Exception $e) {
        echo $e;
    } finally {
        $dotEnv = Dotenv\Dotenv::createUnsafeImmutable('./bot/utils');
        $dotEnv->load();
        return $_ENV[$token];
    }
}