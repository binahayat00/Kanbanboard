<?php
namespace test;
use \PHPUnit_Framework_TestCase;

class KarbanBoardTest extends PHPUnit_Framework_TestCase
{
    public function testFailure()
    {
        $this->assertEmpty(['Valuebound']);
    }
}
?>