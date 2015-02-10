<?php

namespace Drupal\at\Tests;

use Drupal\at_base\Tests\DrupalCacheAPI;
use DrupalWebTestCase;
use ReflectionObject;

/**
 * Drupal take a lot of time to setup environment for each test case. It maybe
 * good for most of cases, but the at.module just provides API functionalities.
 * We don't want Drupal rebuild everything. So this class only has one testing
 * method —  ::testWrapper(), it will find methods from traits, which start with
 * 'check…' and consider them as test cases.
 */
class TestCases extends DrupalWebTestCase
{

    protected $profile = 'testing';

    use \Drupal\at\Tests\CacheTestCaseTrait,
        \Drupal\at\Tests\ContainerTestCaseTrait,
        \Drupal\at\Tests\JsonSchemaTestCaseTrait,
        \Drupal\at\Tests\KeyValueStorageTestCaseTrait,
        \Drupal\at\Tests\DispatcherTestCaseTrait;

    public static function getInfo()
    {
        return array(
            'name'        => 'AT Test cases',
            'description' => '…',
            'group'       => 'AT'
        );
    }

    public function setUp()
    {
        $modules = array('at', 'at_test');
        parent::setUp($modules);
        at()->setDrupalCacheAPI(new DrupalCacheAPI());
    }

    public function testWrapper()
    {
        $rclass = new ReflectionObject($this);
        foreach ($rclass->getMethods() as $method) {
            if (0 === strpos($method->getName(), 'check')) {
                if ('Drupal\at\Tests\TestCases' === $method->class) {
                    $this->assertTrue(TRUE, '[Checking] ' . $method->getName() . '…', 'Debug');
                    $this->{$method->getName()}();
                }
            }
        }
    }

}
