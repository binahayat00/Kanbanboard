<?php

namespace App\Route;

use App\Controllers\GithubController;
use Lib\Router;

class Handler
{

    protected $result;
    public function __construct()
    {
        $this->result;
    }

    public function run()
    {

        Router::get('/', function () {
            $this->result = (new GithubController())->getMilestones();
        });

        //callback from github
        $this->result = ($this->result) ? $this->result : (new GithubController())->getMilestones();

        return $this->result;
    }
}
