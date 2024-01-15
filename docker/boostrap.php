<?php

use M1\Env\Parser;
use app\models\User;

$envFile = realpath(__DIR__.'/../.env');

if ($envFile) {
    $env = file_get_contents($envFile);
    $parser = new Parser($env);
    $vars = $parser->getContent();
    foreach ($vars as $key => $val) {
        if (strpos($key, 'YII_') === 0 && !defined($key)) {
            define($key, $val);
        }
        putenv("$key=$val");
    }
}
