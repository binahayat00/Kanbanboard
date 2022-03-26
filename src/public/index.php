<?php
use KanbanBoard\Authentication;
use KanbanBoard\GithubActual;
use KanbanBoard\Utilities;


require __DIR__ .'/../classes/KanbanBoard/Github.php';
require __DIR__ .'/../classes/Utilities.php';
require __DIR__ .'/../classes/KanbanBoard/Authentication.php';

putenv('GH_CLIENT_ID=9d0ca923417462387807');
putenv('GH_CLIENT_SECRET=2121b07e52d55f7086a062f3808a8eb06fdc45fa');
putenv('GH_ACCOUNT=amirrezazare59');
putenv('GH_REPOSITORIES=project1');

$repositories = explode('|', Utilities::env('GH_REPOSITORIES'));
$authentication = new \KanbanBoard\Login();
$token = $authentication->login();
$github = new GithubClient($token, Utilities::env('GH_ACCOUNT'));
$board = new \KanbanBoard\Application($github, $repositories, array('waiting-for-feedback'));
$data = $board->board();
$m = new Mustache_Engine(array(
	'loader' => new Mustache_Loader_FilesystemLoader('../views'),
));
echo $m->render('index', array('milestones' => $data));
