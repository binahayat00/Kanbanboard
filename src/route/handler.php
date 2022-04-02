<?php

namespace App\Route;

use App\Controllers\GithubController;

class Handler {

    public function boot(){
        $githubController = new GithubController();
        $result = $githubController->getMilestones();
        return $result;
    }

}