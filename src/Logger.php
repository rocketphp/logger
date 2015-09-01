<?php
/**
 * RocketPHP (http://rocketphp.io)
 *
 * @package   RocketPHP
 * @link      https://github.com/rocketphp/logger
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace RocketPHP\Logger;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

/**
 * Logger: Log to file - PSR Log interface
 *
 * Use Logger when you want to log events to static log files.
 */
class Logger
extends AbstractLogger
{

    const DEFAULT_PERMISSIONS = 0777;
    const DEFAULT_FILEEXT     = 'txt';
    const DEFAULT_DATEFORMAT  = 'd-m-Y G:i:s.u';

    const EMERGENCY = LogLevel::EMERGENCY;
    const ALERT     = LogLevel::ALERT;
    const CRITICAL  = LogLevel::CRITICAL;
    const ERROR     = LogLevel::ERROR;
    const WARNING   = LogLevel::WARNING;
    const NOTICE    = LogLevel::NOTICE;
    const INFO      = LogLevel::INFO;
    const DEBUG     = LogLevel::DEBUG;

    /**
     * Log Level Threshold
     * @access private
     * @var    int
     */
    private $_threshold = LogLevel::DEBUG;

    /**
     * Log Levels
     * @access private
     * @var    array
     */
    private $_levels = [
        self::EMERGENCY => 0,
        self::ALERT     => 1,
        self::CRITICAL  => 2,
        self::ERROR     => 3,
        self::WARNING   => 4,
        self::NOTICE    => 5,
        self::INFO      => 6,
        self::DEBUG     => 7,
    ];

    /**
     * Log file object
     * @var LogFile
     */
    public $logFile;

    /**
     * Constructor
     *
     * @param  string  $dir        Logging directory
     * @param  integer $threshold  LogLevel threshold
     * @param  array   $options    Options
     * @return void
     */
    public function __construct($dir, 
                                $threshold = LogLevel::DEBUG, 
                                array $options = array())
    {            
        $permissions = self::DEFAULT_PERMISSIONS;
        $fileExt = self::DEFAULT_FILEEXT;
        $dateFormat = self::DEFAULT_DATEFORMAT;
        if (!empty($options))
            if (array_key_exists("permissions", $options))
                $permissions = $options["permissions"]; 
            if (array_key_exists("file-ext", $options))
                $fileExt = $options["file-ext"];
            if (array_key_exists("date-format", $options))
                $dateFormat = $options["date-format"];
        $this->logFile = new LogFile($dir, $permissions, $fileExt, $dateFormat);
        $this->logFile->open();
        $this->_threshold = $threshold;
    }

    /**
     * Sets date format
     * 
     * @param  string $dateFormat Date format
     * @return void
     */
    public function setDateFormat($dateFormat)
    {
        $this->logFile->dateFormat = $dateFormat;
    }

    /**
     * Sets log level threshold
     * 
     * @param  int $threshold Log level threshold
     * @return void
     */
    public function setLogLevelThreshold($threshold)
    {
        $this->_threshold = $threshold;
    }

    /**
     * Logs message to file
     *
     * @param  mixed  $level
     * @param  string $message
     * @param  array  $context
     * @return void
     */
    public function log($level, $message, array $context = array())
    {
        if ($this->_levels[$this->_threshold] < $this->_levels[$level])
            return;
        else
            $this->logFile->write($level, $message, $context);
    }

}