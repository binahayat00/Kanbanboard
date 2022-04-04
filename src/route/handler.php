<?php

namespace App\Route;

use App\Controllers\GithubController;
use Lib\Response;
use Lib\Router;

class Handler {

    public function boot(){
        $result = null;
        Router::get($_SERVER['REQUEST_URI'], function () {
            $result = (new GithubController())->getMilestones();

        });
        // $result = null;
        // $curdir = dirname($_SERVER['REQUEST_URI'])."/";
        // $baseUri = str_replace($curdir, '/', dirname($_SERVER['REQUEST_URI']));
        // var_dump($baseUri);die;
        var_dump($result);die;
        return $result;
    }

}