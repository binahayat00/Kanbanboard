<?php

declare(strict_types=1);

namespace Tests\Mocks\Controllers;

use App\Controllers\GithubController;
use PHPUnit\Framework\TestCase;

class GithubTest extends TestCase
{
    public function testGetMilestones()
    {
        $mock = $this->createMock(GithubController::class);
        $html = "<html></html>";
        $mock->expects($this->once())
            ->method('getMilestones')
            ->with()
            ->willReturn($html);

        $this->assertEquals($html, $mock->getMilestones());
    }
}
