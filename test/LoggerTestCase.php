<?php
/**
 * RocketPHP (http://rocketphp.io)
 *
 * @package   RocketPHP
 * @link      https://github.com/rocketphp/logger
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace RocketPHPTest\Logger;

/** 
 * Test case for Logger
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