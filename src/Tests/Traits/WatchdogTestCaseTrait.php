<?php

namespace Drupal\at\Tests\Traits;

trait WatchdogTestCaseTrait
{

    public function checkWatchdogService()
    {
        $msg = 'watchdog service is available and implementing Psr\Log\LoggerInterface';
        $this->assertTrue(at()->watchdog() instanceof \Psr\Log\LoggerInterface, $msg);
    }

    public function checkIfWatchdogReallyLoggedDataToDrupal()
    {
        // get test service
        $test_service = at()->get('at_test.service_with_logger');
        $expecting = array('message' => 'bar', 'level' => 'debug');

        // Action: execute method which will call logger
        $test_service->myLogic($expecting['message'], $expecting['level']);
        $actual = variable_get('at_test_watchdog');

        // Checking
        $msg = 'watchdog service should log message to Drupal\'s watchdog';
        $this->assertEqual($expecting, $actual, $msg);
    }

}
