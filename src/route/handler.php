<?php

namespace App\Route;

use App\Controllers\GithubController;
use Lib\Response;
use Lib\Router;

class Handler {
    
    protected $result;
    public function __construct()
    {
        $this->result;
    }

    public function boot(){

        //every route
        Router::get($_SERVER['REQUEST_URI'], function () {
             $this->result = (new GithubController())->getMilestones();
        });

        return $this->result;
    }



}