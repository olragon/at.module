<?php

namespace Drupal\at\Tests;

use Drupal\at_base\Tests\DrupalCacheAPI;
use DrupalWebTestCase;
use ReflectionObject;

class TestCases extends DrupalWebTestCase
{

    protected $profile = 'testing';

    use \Drupal\at\Tests\Traits\CacheTestCaseTrait,
        \Drupal\at\Tests\Traits\ContainerTestCaseTrait,
        \Drupal\at\Tests\Traits\JsonSchemaTestCaseTrait,
        \Drupal\at\Tests\Traits\KeyValueStorageTestCaseTrait;

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
        parent::setUp('at');
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
