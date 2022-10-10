<?php

function getEnvironmentVariable(string $token): string
{
    try {
        include "./api/.env";
    } catch (\mysql_xdevapi\Exception $e) {
        echo $e;
    } finally {
        $dotEnv = Dotenv\Dotenv::createUnsafeImmutable('./');
        $dotEnv->load();
        return $_ENV[$token];
    }
}