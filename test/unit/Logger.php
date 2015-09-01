<?php 
namespace RocketPHPTest\Logger;
use RocketPHP\Logger\Logger;
/**
 * @group RocketPHP_Logger
 */ 
class Logger_UnitTest
extends LoggerTestCase
{

    public function testConstructor()
    {
        $logger = new Logger($this->dir); 
        $this->assertInstanceOf('RocketPHP\\Logger\\Logger', $logger);
    }

    public function testConstructorSetsLogFile()
    {
        $logger = new Logger($this->dir); 
        $this->assertInstanceOf('RocketPHP\\Logger\\LogFile', $logger->logFile);
    }

    /**
     * @dataProvider             badValues
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Expected string for directory
     */
    public function testConstructorInvalidDir($badValue)
    {  
        $logger = new Logger($badValue);
    }

}