<?php

function getEnvironmentVariable(string $token): ?string
{
    $file = fopen("./.env", "r");
    $filetext = fread($file, filesize("./.env"));
    $environmentVariable = explode("\n", $filetext);

    for ($i = 0; $i < count($environmentVariable); $i++) {
        if (str_contains($environmentVariable[$i],$token)) {
            fclose($file);
            return explode("=",$environmentVariable[$i])[1];
        }
    }
    fclose($file);
    return null;
}

getEnvironmentVariable("WEB_HOOK_URL");