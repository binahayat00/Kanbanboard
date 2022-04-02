<?php

namespace Route;

use Controllers\GithubController;

class Handler {

    public function boot(){
        $githubController = new GithubController();
        $result = $githubController->getMilestones();
        return $result;
    }

}