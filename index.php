<?php
include_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
// putenv($_ENV);
// if(getenv('APP_ENV') === 'development') {
//     $dotenv->load(__DIR__);
// }
// $dotenv->required('OTHER_VAR');
$project_name = getenv('NAME');
var_dump($project_name);


var_dump($_ENV['PROJECT_NAME']);die;
require_once __DIR__.'/src/public/index.php';

?>