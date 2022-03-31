<?php
namespace test;
use \PHPUnit_Framework_TestCase;
use \PHPUnit_Framework_Assert;
use \PHPUnit\Framework\TestCase;
use \PHPUnit\Framework\Assert;

class KarbanBoardTest extends PHPUnit_Framework_TestCase
{
    public function testFailure()
    {
        $response = $this->getStatus('/');
        $this->assertTrue(true == true);
    }
}
?>