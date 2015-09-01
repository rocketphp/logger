<?php
/**
 * RocketPHP (http://rocketphp.io)
 *
 * @package   RocketPHP
 * @link      https://github.com/rocketphp/logger
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace RocketPHP\Logger;

use DateTime;
use RuntimeException;
use InvalidArgumentException;

/** 
 * Log File
 *
 * Handles log file I/O.
 */
class LogFile
{
    public $dir;
    public $path;
    private $_fh;
    private $_permissions;
    private $_fileExt; 
    private $_dateFormat; 

    /**
     * Constructor
     *
     * @param string $dir         Directory path
     * @param int    $permissions Directory permissions
     * @param string $fileExt     File extension
     * @param string $dateFormat  Date format
     */
    public function __construct($dir, $permissions, $fileExt, $dateFormat)
    {
        if(!is_string($dir))
            throw new InvalidArgumentException(
                "Expected string for directory.", 
                1
            );
        $this->_permissions = $permissions;
        $this->_fileExt     = $fileExt;
        $this->_dateFormat  = $dateFormat;
        $this->_setPath($dir);
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        if ($this->_fh) {
            fclose($this->_fh);
        }
    }

    /**
     * Opens log file for writing
     *
     * @return void
     * @throws RuntimeException If file cannot be opened
     */
    public function open()
    {
        $this->_fh = fopen($this->path, 'a');
        if (!$this->_fh) {
            throw new RuntimeException(
                'The file could not be opened. Check permissions.',
                1
            );
        }
    }

    /**
     * Sets file path and creates directory if required
     *
     * @param  string $dir Directory to save log file
     * @return void
     * @throws RuntimeException If file is not writable
     */
    private function _setPath($dir)
    {
        $this->dir = rtrim($dir, '\\/');
        $this->path = $this->dir . 
                        DIRECTORY_SEPARATOR . 'log_' . 
                        date('d-m-Y') . '.' . 
                        $this->_fileExt;
        if (!file_exists($this->dir)) {
            mkdir($this->dir, $this->_permissions, true);
        }
        if (file_exists($this->path) && !is_writable($this->path)) {
            throw new RuntimeException(
                'The file could not be written to. 
                Check that appropriate permissions have been set.',
                1
            );
        }
    }

    /**
     * Writes to file
     *
     * @param  string $level   The Log Level of the message
     * @param  string $message The message to log
     * @param  array  $context The context
     * @return void
     * @throws RuntimeException If file could not be written
     */
    public function write($level, $message, $context)
    {
        $message = $this->_formatMessage($level, $message, $context); 
        if (!is_null($this->_fh)) {
            if (fwrite($this->_fh, $message) === false) {
                throw new RuntimeException(
                    'The file could not be written to. 
                    Check that appropriate permissions have been set.',
                    1
                );
            }
        }
    }

    /**
     * Formats message
     *
     * @param  string $level   The Log Level of the message
     * @param  string $message The message to log
     * @param  array  $context The context
     * @return string
     */
    private function _formatMessage($level, $message, $context)
    {
        // if message is array - json encode to string
        if (is_array($message)) {
            $message = json_encode($message);
        }
        $level = strtoupper($level);
        if (!empty($context)) {
            $message .= PHP_EOL . 
                        $this->_indent($this->_contextToString($context));
        }
        return "[{$this->_getTimestamp()}] [{$level}] {$message}".PHP_EOL;
    }

    /**
     * Gets timestamp
     * 
     * @return string
     */
    private function _getTimestamp()
    {
        $originalTime = microtime(true);
        $micro = sprintf(
            "%06d", 
            ($originalTime - floor($originalTime)) 
            * 1000000
        );
        $date = new DateTime(date('Y-m-d H:i:s.'.$micro, $originalTime));

        return $date->format($this->_dateFormat);
    }

    /**
     * Converts context to string
     * 
     * @param  array $context The Context
     * @return string
     */
    private function _contextToString($context)
    {
        $export = '';
        foreach ($context as $key => $value) {
            $export .= "{$key}: ";
            $export .= preg_replace(
                [
                '/=>\s+([a-zA-Z])/im',
                '/array\(\s+\)/im',
                '/^  |\G  /m',
                ], 
                [
                '=> $1',
                'array()',
                '    ',
                ], 
                str_replace('array (', 'array(', var_export($value, true))
            );
            $export .= PHP_EOL;
        }
        return str_replace(['\\\\', '\\\''], ['\\', '\''], rtrim($export));
    }

    /**
     * Indents message
     * 
     * @param  string $string The string to indent
     * @param  string $indent What to use as the indent.
     * @return string
     */
    private function _indent($string, $indent = '    ')
    {
        return $indent.str_replace("\n", "\n".$indent, $string);
    }
}