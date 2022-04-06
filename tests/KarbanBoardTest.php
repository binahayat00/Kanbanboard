<?php declare(strict_types=1);

namespace Tests;

use App\Controllers\GithubController;
use PHPUnit\Framework\TestCase;

class KarbanBoardTest extends TestCase
{
    protected $githubController;
    protected function setUp(): void{
        parent::setUp();
        $_SESSION=[];
        $this->githubController = new GithubController();
    }

    public function testGetRepositoriesGithubInEnv()
    {
        $response = $this->githubController->getRepositoriesGithubInEnv();
        $this->assertEquals($_ENV['GH_REPOSITORIES'] ,$response);
    }

    public function testGetAccountGithubInEnv(){
        $response = $this->githubController->getAccountGithubInEnv();
        $this->assertEquals($_ENV['GH_ACCOUNT'] ,$response);
    }

    public function testGetRepositories(){
        $response = $this->githubController->getRepositories();
        $this->assertIsArray($response);
    }

    public function testLogin(){
        $response = $this->githubController->loginInGithub();
        $this->expectException('return test');

        $this->assertTrue(true);
    }
    
}
?>