<?php

namespace Controllers;

use Mustache_Loader_FilesystemLoader;
use KanbanBoard\Authentication;
use KanbanBoard\Application;
use KanbanBoard\Utilities;
use Mustache_Engine;
use GithubClient;

class GithubController
{
    public function __construct()
    {
    }

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

    public function newGithubClientObject($token,$account){
        return new GithubClient($token,$account);
    }

    public function newApplicationObject($github, $repositories,$paused_labels = array('waiting-for-feedback')){
        return new Application($github, $repositories, $paused_labels);
    }

    public function newMustacheLoaderFilesystemLoaderObject($route = __DIR__ .'/../views'){
        return new Mustache_Loader_FilesystemLoader($route);
    }

    public function newMustacheEngineObject($loader){
        return new Mustache_Engine(array(
            'loader' => $loader,
        ));
    }

    public function getGithubData($repositories,$token,$account){
        $github = $this->newGithubClientObject($token,$account);
        $board = $this->newApplicationObject($github, $repositories);
        return $board->board();
    }

    public function getMustache($data){
        $loader = $this->newMustacheLoaderFilesystemLoaderObject();
        $mustache = $this->newMustacheEngineObject($loader);
        return $mustache->render('index', array('milestones' => $data));
    }

    public function run(){
        $repositories = $this->getRepositories();
        $token = $this->loginInGithub();
        $account = $this->getAccountGithubInEnv();
        $data = $this->getGithubData($repositories,$token,$account);
        return $this->getMustache($data);
    }
    
}