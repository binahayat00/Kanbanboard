<?php
use Route\Handler;

require __DIR__ .'/../../bootstrap/app.php';

$githubController = new Handler();

echo $githubController->boot();


