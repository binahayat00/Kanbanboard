<?php
include_once __DIR__ . '/vendor/autoload.php';
// $dotenv = new Dotenv\Dotenv(__DIR__);
// $dotenv->load();
var_dump(getenv('PROJECT_NAME'));
var_dump($_ENV['PROJECT_NAME']);die;
require_once __DIR__.'/src/public/index.php';

?>