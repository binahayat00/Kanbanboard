<?php

namespace test;

use App\Controllers\GithubController;
use \PHPUnit\Framework\TestCase;

class KarbanBoardTest extends TestCase
{
    public function testrunproject()
    {
        var_dump($_ENV);die;
        $githubController = new GithubController();
        $response = $githubController->getMilestones();
        $response = $this->getStatus('/');
        //var_dump($response);die;
        $this->assertTrue($response == 200);
    }
}
?>