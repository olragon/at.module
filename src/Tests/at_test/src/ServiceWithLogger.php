<?php

namespace Drupal\at_test;

use \Drupal\at\Watchdog;

class ServiceWithLogger
{

    public function __construct(Watchdog $logger)
    {
        $this->logger = $logger;
    }

    public function getLogger()
    {
        return $this->logger;
    }

    public function myLogic($message, $level)
    {
        $this->getLogger()->{$level}($message);
    }
}