<?php
use Controllers\GithubController;

require __DIR__ .'/../bootstrap/app.php';

$githubController = new GithubController();

echo $githubController->run();


