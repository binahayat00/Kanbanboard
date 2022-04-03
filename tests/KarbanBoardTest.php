<?php declare(strict_types=1);

namespace Tests;

use App\Controllers\GithubController;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

class KarbanBoardTest extends TestCase
{

    public function testGetRepositoriesGithubInEnv()
    {
        $githubController = new GithubController();
        $response = $githubController->getRepositoriesGithubInEnv();
        $this->assertEquals($_ENV['GH_REPOSITORIES'] ,$response);
    }

    public function testGetAccountGithubInEnv(){
        $githubController = new GithubController();
        $response = $githubController->getAccountGithubInEnv();
        $this->assertEquals($_ENV['GH_ACCOUNT'] ,$response);
    }

    public function testGetRepositories(){
        $githubController = new GithubController();
        $response = $githubController->getRepositories();
        $this->assertIsArray($response);
    }
    
}
?>