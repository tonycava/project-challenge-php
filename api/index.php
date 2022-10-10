<?php

require __DIR__ . '/vendor/autoload.php';

app()->get('/', function () {
    response()->json([
        'greeting' => "Hell"
    ]);
});

app()->run();