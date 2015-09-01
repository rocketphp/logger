<?php 
namespace RocketPHPTest\Logger;
use RocketPHP\Logger\Logger;
/**
 * @group RocketPHP_Logger
 */ 
class Logger_Functional
extends LoggerTestCase
{

    public function testConstructor()
    {
        $logger = new Logger($this->dir); 
        $this->assertInstanceOf('RocketPHP\\Logger\\Logger', $logger);
    }
}