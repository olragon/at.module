<?php

namespace Drupal\at\Tests;

trait ContainerTestCaseTrait
{

    public function checkServiceContainer()
    {
        /* @var $testCase \DrupalWebTestCase */
        $testCase = $this;

        $container = at()->getContainer();
        $testCase->assertEqual('AT_Container', get_class($container));
        $testCase->assertTrue(method_exists($container, 'getServiceIds'));

        // Get expression language service
        $expLang = $container->get('expression_language');
        $testCase->assertEqual('Symfony\Component\ExpressionLanguage\ExpressionLanguage', get_class($expLang));
    }

}
