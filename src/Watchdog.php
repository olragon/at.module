<?php

namespace Drupal\at;

use Psr\Log\LoggerInterface;

class Watchdog implements LoggerInterface
{

    public $defaultContext = array();

    function __construct($context = array()) {
        $defaultContext = array(
            'type' => 'at',
            'variables' => array(),
            'link' => ''
        );

        $this->defaultContext = $context + $defaultContext;
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function emergency($message, array $context = array())
    {
        list($type, $variables, $link) = $this->_contextHandler($context);
        watchdog($type, $message, $variables, WATCHDOG_EMERGENCY, $link);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function alert($message, array $context = array())
    {
        list($type, $variables, $link) = $this->_contextHandler($context);
        watchdog($type, $message, $variables, WATCHDOG_ALERT, $link);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function critical($message, array $context = array())
    {
        list($type, $variables, $link) = $this->_contextHandler($context);
        watchdog($type, $message, $variables, WATCHDOG_CRITICAL, $link);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function error($message, array $context = array())
    {
        list($type, $variables, $link) = $this->_contextHandler($context);
        watchdog($type, $message, $variables, WATCHDOG_ERROR, $link);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function warning($message, array $context = array())
    {
        list($type, $variables, $link) = $this->_contextHandler($context);
        watchdog($type, $message, $variables, WATCHDOG_WARNING, $link);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function notice($message, array $context = array())
    {
        list($type, $variables, $link) = $this->_contextHandler($context);
        watchdog($type, $message, $variables, WATCHDOG_NOTICE, $link);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function info($message, array $context = array())
    {
        list($type, $variables, $link) = $this->_contextHandler($context);
        watchdog($type, $message, $variables, WATCHDOG_INFO, $link);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function debug($message, array $context = array())
    {
        list($type, $variables, $link) = $this->_contextHandler($context);
        watchdog($type, $message, $variables, WATCHDOG_DEBUG, $link);
    }

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
        list($type, $variables, $link) = $this->_contextHandler($context);
        watchdog($type, $message, $variables, WATCHDOG_NOTICE, $link);
    }

    /**
     * Build default context
     *
     * @param $context
     *
     * @return mixed
     */
    private function _contextHandler($context)
    {
        return array_values($context + $this->defaultContext);
    }
}