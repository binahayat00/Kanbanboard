<?php

include_once __DIR__ . '/../vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load();

use App\Route\Handler;

$handler = new Handler();
echo $handler->run();


