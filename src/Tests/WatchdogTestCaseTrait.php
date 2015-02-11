<?php

namespace Drupal\at\Tests;

trait WatchdogTestCaseTrait
{
    public function checkWatchdogService()
    {
        $msg = 'watchdog service is available and implement Psr\Log\LoggerInterface';
        // get watchdog service
        $watchdog = at()->watchdog();
        // check if watchdog service is class which implement Psr\Log\LoggerInterface
        $this->assertTrue(in_array('Psr\Log\LoggerInterface', class_implements($watchdog)), $msg);
    }

    public function checkIfWatchdogReallyLoggedDataToDrupal()
    {
        $msg = 'watchdog service should log message to Drupal\'s watchdog';
        // get test service
        $test_service = at()->get('at_test.service_with_logger');
        $expecting = array(
            'message' => 'bar',
            'level' => 'debug'
        );
        // execute method which will call logger
        $test_service->myLogic($expecting['message'], $expecting['level']);
        $actual = variable_get('at_test_watchdog');
        $this->assertEqual($expecting, $actual, $msg);
    }
}