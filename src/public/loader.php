<?php

use App\Route\Handler;

require __DIR__ .'/../../bootstrap/app.php';
require __DIR__ .'/../route/handler.php';

$handler = new Handler();
echo $handler->run();


