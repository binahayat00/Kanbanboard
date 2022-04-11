<?php

namespace App\Route;

use App\Controllers\GithubController;
use Lib\Router;

class Handler
{

    protected $result;

    public function run()
    {

        Router::get('/', function () {
            $this->result = (new GithubController())->getMilestones();
        });

        $this->callbackFromGithub();

        return $this->result;
    }

    public function callbackFromGithub(): void{
        $this->result = ($this->result) ? $this->result : (new GithubController())->getMilestones();
    }
}
