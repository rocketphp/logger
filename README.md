# logger

[![Build Status](https://travis-ci.org/rocketphp/logger.svg?branch=master)](https://travis-ci.org/rocketphp/logger)

`RocketPHP\Logger` uses the PSR Log interface to log data to a static file.

**_To log data_** – use the emergency, alert, critical, error, warning, notice, info and debug methods.

```php
use RocketPHP\Logger as Logger;

$logger = new Logger(
                     'log', 
                     Logger::CRITICAL
          );
$logger->emergency('Logged');
$logger->warning('NOT logged');
```

- File issues at https://github.com/rocketphp/logger/issues
- Documentation is at http://rocketphp.io/logger
