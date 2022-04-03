<?php

namespace App\Controllers;

use Mustache_Loader_FilesystemLoader;
use App\Classes\KanbanBoard\Authentication;
use App\Classes\KanbanBoard\Application;
use App\Classes\Utilities;
use Mustache_Engine;
use App\Classes\KanbanBoard\GithubClient;

class GithubController
{

    public function getRepositoriesGithubInEnv(){
        return Utilities::env('GH_REPOSITORIES');
    }

    public function getAccountGithubInEnv(){
        return Utilities::env('GH_ACCOUNT');
    }

    public function getRepositories(){
        return explode('|', $this->getRepositoriesGithubInEnv());
    }

    public function loginInGithub(){
        $Authentication = new Authentication();
        return $Authentication->login();
    }

    public function getGithubData($repositories,$token,$account){
        $github = new GithubClient($token,$account);
        $board = new Application($github, $repositories, $paused_labels = array('waiting-for-feedback'));
        return $board->board();
    }

    public function getMustache($data,$route){
        $loader = new Mustache_Loader_FilesystemLoader($route);
        $mustache = new Mustache_Engine(['loader' => $loader]);
        return $mustache->render('index', array('milestones' => $data));
    }

    public function getMilestones(){
        $repositories = $this->getRepositories();
        $token = $this->loginInGithub();
        $account = $this->getAccountGithubInEnv();
        $data = $this->getGithubData($repositories,$token,$account);
        return $this->getMustache($data,$route = __DIR__ .'/../views');
    }
    
}