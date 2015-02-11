<?php

namespace Drupal\at;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;
use UnexpectedValueException;

class Watchdog extends AbstractLogger
{

    private $defaultContext = array(
        'type'      => 'at',
        'variables' => array(),
        'link'      => ''
    );

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $context = array())
    {
        list($type, $variables, $link) = $this->buildParams($context);
        watchdog($type, $message, $variables, $this->convertLogLevelToWatchdogServerity($level), $link);
    }

    private function convertLogLevelToWatchdogServerity($level)
    {
        switch ($level) {
            case LogLevel::EMERGENCY:
                return WATCHDOG_EMERGENCY;
            case LogLevel::ALERT:
                return WATCHDOG_ALERT;
            case LogLevel::CRITICAL:
                return WATCHDOG_CRITICAL;
            case LogLevel::ERROR:
                return WATCHDOG_ERROR;
            case LogLevel::WARNING:
                return WATCHDOG_WARNING;
            case LogLevel::NOTICE:
                return WATCHDOG_NOTICE;
            case LogLevel::INFO:
                return WATCHDOG_INFO;
            case LogLevel::DEBUG:
                return WATCHDOG_DEBUG;
        }
        throw new UnexpectedValueException(sprintf('Invalid log level: %s', \filter_xss_admin($level)));
    }

    /**
     * Build default context
     *
     * @param $context
     * @return mixed[]
     */
    private function buildParams($context)
    {
        return array_values($context + $this->defaultContext);
    }

}
