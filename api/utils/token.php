<?php

function getEnvironmentVariableApi(string $token): ?string
{
    $file = fopen("./.env", "r");
    $filetext = fread($file, filesize("./.env"));
    $environmentVariable = explode("\n", $filetext);

    for ($i = 0; $i < count($environmentVariable); $i++) {
        if (str_contains($environmentVariable[$i],$token)) {
            fclose($file);
            echo explode("=",$environmentVariable[$i])[1] . "\n";
            return explode("=",$environmentVariable[$i])[1];
        }
    }
    fclose($file);
    return null;
}

getEnvironmentVariableApi("TELEGRAM_API_TOKEN");