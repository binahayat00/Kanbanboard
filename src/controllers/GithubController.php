<?php

namespace App\Controllers;

use Mustache_Loader_FilesystemLoader;
use App\Classes\KanbanBoard\Authentication;
use App\Classes\KanbanBoard\Application;
use App\Classes\Utilities;
use Mustache_Engine;
use App\Classes\KanbanBoard\GithubClient;
use Exception;
use Lib\Config;

class GithubController
{

    public function getRepositoriesGithubInEnv()
    {
        return Utilities::env('GH_REPOSITORIES');
    }

    public function getAccountGithubInEnv()
    {
        return Utilities::env('GH_ACCOUNT');
    }

    public function getRepositories(): array
    {
        return explode('|', $this->getRepositoriesGithubInEnv());
    }

    public function loginInGithub(): ?string
    {
        $Authentication = new Authentication();
        return $Authentication->login();
    }

    public function getGithubData(array $repositories,string $token,string $account)
    {
        $github = new GithubClient($token, $account);
        $board = new Application($github, $repositories, $paused_labels = array('waiting-for-feedback'));
        return $board->board();
    }

    public function getMustache(array $data,string $route = null)
    {
        $route = ($route) ? $route : Config::get('VIEW_ROUTE');
        $loader = new Mustache_Loader_FilesystemLoader($route);
        $mustache = new Mustache_Engine(['loader' => $loader]);
        return $mustache->render('index', array('milestones' => $data));
    }

    public function getMilestones()
    {
        try {
            $repositories = $this->getRepositories();
            $token = $this->loginInGithub();
            $account = $this->getAccountGithubInEnv();
            $data = $this->getGithubData($repositories, $token, $account);
            return $this->getMustache($data);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
