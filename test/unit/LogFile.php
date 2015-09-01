<?php 
namespace RocketPHPTest\Logger;
use RocketPHP\Logger\Logger;
use RocketPHP\Logger\LogFile;
/**
 * @group RocketPHP_Logger
 */ 
class LogFile_UnitTest
extends LoggerTestCase
{

    public function testConstructorSetsDir()
    {
        $logger = new LogFile(
                            $this->dir, 
                            Logger::DEFAULT_PERMISSIONS,
                            Logger::DEFAULT_FILEEXT, 
                            Logger::DEFAULT_DATEFORMAT
                        ); 
        $this->assertSame($this->dir, $logger->dir);
    }

    public function testConstructorSetsPath()
    {
        $logger = new LogFile(
                            $this->dir, 
                            Logger::DEFAULT_PERMISSIONS,
                            Logger::DEFAULT_FILEEXT, 
                            Logger::DEFAULT_DATEFORMAT
                        ); 
        $this->assertSame($this->dir . DIRECTORY_SEPARATOR . 
                        'log_' . date('d-m-Y') . '.' . 
                        Logger::DEFAULT_FILEEXT, 
                        $logger->path);
    }
    
    /**
     * @dataProvider             badValues
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Expected string for directory
     */
    public function testConstructorThrowsExceptionIfInvalidDir($badValue)
    {  
        $logger = new LogFile(
                            $badValue, 
                            Logger::DEFAULT_PERMISSIONS,
                            Logger::DEFAULT_FILEEXT, 
                            Logger::DEFAULT_DATEFORMAT
                        ); 
    }

}