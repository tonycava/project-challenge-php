<?php

function getEnvironmentVariable(string $token): string
{
    try {
        include "./bot/.env";
    } catch (\mysql_xdevapi\Exception $e) {
        echo $e;
    } finally {
        $dotEnv = Dotenv\Dotenv::createUnsafeImmutable('./');
        $dotEnv->load();
        return $_ENV[$token];
    }
}