<?php

namespace Tests\Mocks\Classes;

use App\Classes\KanbanBoard\Application;
use App\Classes\KanbanBoard\Authentication;
use App\Classes\KanbanBoard\GithubClient;
use PHPUnit\Framework\TestCase;

class KarbanBoardTest extends TestCase
{
    public function testLoginWithMock()
    {
        $api = $this->createMock(Authentication::class);
        $token = '1f61f699f1069f60xxx';

        $api->expects($this->once())
            ->method('login')
            ->with()
            ->willReturn($token);

        $this->assertEquals($token, $api->login());
    }

    public function testMilestones()
    {
        $repository = $_ENV['GH_REPOSITORIES'];
        $result = [
            "url" => "https://api.github.com/repos/",
            "html_url" => "https://github.com/"
        ];

        $api = $this->createMock(GithubClient::class);
        $api->expects($this->once())
            ->method('milestones')
            ->with($repository)
            ->willReturn($result);

        $this->assertEquals($result, $api->milestones($repository));
    }

    public function testIssues()
    {
        $repository = $_ENV['GH_REPOSITORIES'];
        $milestone_id = 1;
        $result = [
            "url" => "https://api.github.com/repos/",
            "repository_url" => "https://github.com/repos/"
        ];

        $api = $this->createMock(GithubClient::class);
        $api->expects($this->once())
            ->method('issues')
            ->with($repository)
            ->willReturn($result);

        $this->assertEquals($result, $api->issues($repository, $milestone_id));
    }

    public function testBoard()
    {
        $repository = $_ENV['GH_REPOSITORIES'];
        $milestone_id = 1;
        $result = [
            "milestone" => "project1",
            "url" => "https://github.com/username/filled/milestone/1",
            "progress" => [
                "total" => [
                    "complete" => 0,
                    "remaining" => 3
                ],
                "percent" => 0
            ]
        ];

        $mock = $this->createMock(Application::class);
        $mock->expects($this->once())
            ->method('board')
            ->with($repository)
            ->willReturn($result);

        $this->assertEquals($result, $mock->board($repository, $milestone_id));
    }
}
