<?php

function getEnvironmentVariable(string $token): ?string
{
    $file = fopen("./.env", "r");
    $filetext = fread($file, filesize("./.env"));
    $environmentVariable = explode("\n", $filetext);
    $value = null;

    foreach ($environmentVariable as $var) {
        $keyValuePair = explode("=", $var);

        if ($keyValuePair[0] === $token) {
            $value = $keyValuePair[1];
            break;
        }
    }
    fclose($file);
    return $value;
}
