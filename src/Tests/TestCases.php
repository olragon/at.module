<?php

namespace Drupal\at\Tests;

use Drupal\at_base\Tests\DrupalCacheAPI;

class TestCases extends \DrupalWebTestCase
{

    protected $profile = 'testing';

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

    public function testAll()
    {
        $rclass = new \ReflectionObject($this);
        foreach ($rclass->getMethods() as $method) {
            if (0 === strpos($method->getName(), 'check') && 'AtModuleTestCases' === $method->class) {
                $this->{$method->getName()}();
            }
        }
    }

}
