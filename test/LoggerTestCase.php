<?php namespace RocketPHPTest\Logger;
/** 
 * Logger Test Case
 *
 */ 
abstract class LoggerTestCase
extends \PHPUnit_Framework_TestCase
{
    public $dir;

    public function badValues()
    {
       return [
           [-1],
           [1],
           [false],
           [array()]
       ];
    }
    
    public function setUp()
    {
        $this->dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'logger-test';
    }

    public function tearDown()
    { 
        // unlink($this->dir);
    }
}