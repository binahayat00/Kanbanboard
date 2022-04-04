<?php

namespace Tests;

use App\Controllers\GithubController;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertTrue;

class LoginToGithubTest extends TestCase
{
    protected $githubController;
    protected function setUp(): void{
        $this->githubController = new GithubController();
    }

    // public function testLogin()
    // {
    //     // header('Location : http://foo.com');
    //     $response = $this->githubController->loginInGithub();
    //     $this->assertEquals(422, $this->response->status());
    // }


    public function testSession(): void
    {
        \session_start();
        \session_create_id();
        $this->assertTrue(true);

        return;
    }
}