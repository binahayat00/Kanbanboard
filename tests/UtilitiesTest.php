<?php declare(strict_types=1);

namespace Tests;

use App\Classes\Utilities;
use PHPUnit\Framework\TestCase;

class UtilitiesTest extends TestCase
{

    public function testDump(){
        $response = Utilities::dump('ttt');
    }

    public function testHasValue(){
        $response = Utilities::hasValue(['1'=>'ttt'],1);
    }

    public function testEnv(){
        $response = Utilities::env('ERROR');
    }
}