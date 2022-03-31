<?php

require __DIR__ .'/../classes/KanbanBoard/GithubClient.php';
require __DIR__ .'/../classes/Utilities.php';
require __DIR__ .'/../classes/KanbanBoard/Authentication.php';
require __DIR__ .'/../controllers/GithubController.php';

if (file_exists(__DIR__ .'/../../test/KarbanBoardTest.php')) {
    require __DIR__ .'/../../test/KarbanBoardTest.php';
}