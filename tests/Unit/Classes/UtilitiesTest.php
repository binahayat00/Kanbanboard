<?php

declare(strict_types=1);

namespace Tests\Unit\Classes;

use App\Classes\Utilities;
use PHPUnit\Framework\TestCase;

class UtilitiesTest extends TestCase
{

    public function testDump()
    {
        $response = Utilities::dump('Test for dump function in Utilities Class');
        $this->assertEmpty($response);
    }

    public function testHasValue()
    {
        $response = Utilities::hasValue(['1' => 'Test for hasValue function in Utilities Class'], 1);
        $this->assertTrue($response);
    }

    public function testEnv()
    {
        $response = Utilities::env('APP_ENV');
        $this->assertEquals($_ENV['APP_ENV'], $response);
    }
}
