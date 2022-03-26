<?php
include_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
putenv("NAME=localserver");
var_dump(getenv('NAME'));
var_dump($_ENV['NAME']);die;
require_once __DIR__.'/src/public/index.php';

?>