<?php
use KanbanBoard\Authentication;
use KanbanBoard\GithubActual;
use KanbanBoard\Utilities;


require __DIR__ .'/../classes/KanbanBoard/Github.php';
require __DIR__ .'/../classes/Utilities.php';
require __DIR__ .'/../classes/KanbanBoard/Authentication.php';



$repositories = explode('|', Utilities::env('GH_REPOSITORIES'));
$authentication = new \KanbanBoard\Login();
$token = $authentication->login();

$github = new GithubClient($token, Utilities::env('GH_ACCOUNT'));
$board = new \KanbanBoard\Application($github, $repositories, array('waiting-for-feedback'));
$data = $board->board();
$mustache = new Mustache_Engine(array(
	'loader' => new Mustache_Loader_FilesystemLoader(__DIR__ .'/../views'),
));
echo $mustache->render('index', array('milestones' => $data));
