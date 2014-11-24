<?php

namespace Drupal\at\Tests;

trait CacheTestCaseTrait
{

    public function checkCacheGetSet()
    {
        $time = at_cache('at:test:1', function() {
            return REQUEST_TIME;
        });
        $this->assertEqual(REQUEST_TIME, $time);
    }

}
