<?php

namespace test;

use Controllers\GithubController;
use \PHPUnit\Framework\TestCase;

class KarbanBoardTest extends TestCase
{
    public function testrunproject()
    {
        $githubController = new GithubController();
        $response = $githubController->gettest();
        var_dump($response);die;
        $response = $this->getStatus('/');
        //var_dump($response);die;
        $this->assertTrue($response == 200);
    }
}
?>