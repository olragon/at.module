<?php

namespace Drupal\at\Tests;

use Drupal\at_base\Tests\DrupalCacheAPI;
use DrupalWebTestCase;
use ReflectionObject;

define('AT_TEST_ROOT', __DIR__);

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

    /** Account for executing /devel/php */
    private $userDevel;

    use \Drupal\at\Tests\Traits\CacheTestCaseTrait,
        \Drupal\at\Tests\Traits\ContainerTestCaseTrait,
        \Drupal\at\Tests\Traits\DispatcherTestCaseTrait,
        \Drupal\at\Tests\Traits\JsonSchemaTestCaseTrait,
        \Drupal\at\Tests\Traits\KeyValueStorageTestCaseTrait,
        \Drupal\at\Tests\Traits\WatchdogTestCaseTrait;

    public static function getInfo()
    {
        return array(
            'name'        => '@module',
            'description' => 'Test cases for @module',
            'group'       => 'AT'
        );
    }

    public function setUp()
    {
        $modules = array('at', 'at_test', 'devel');
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

    public function doEval($code)
    {
        if (NULL === $this->userDevel) {
            $this->userDevel = $this->drupalCreateUser(['execute php code']);
            $this->drupalLogin($this->userDevel);
        }
        $this->drupalPost('devel/php', ['code' => $code], t('Execute'));
    }

}
