<?php

namespace Drupal\at\Tests;

use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

trait ContainerTestCaseTrait
{

    public function checkServiceContainer()
    {
        $container = at()->getContainer();
        $this->assertEqual('AT_Container', get_class($container));
        $this->assertTrue(method_exists($container, 'getServiceIds'));

        // Get expression language service
        $expLang = $container->get('expression_language');
        $this->assertEqual('Symfony\Component\ExpressionLanguage\ExpressionLanguage', get_class($expLang));

        // Test module enable / disable
        $test_service = at()->get('at_test');
        $this->assertEqual('Drupal\at_test\ATTest', get_class($test_service), 'Listen to cache flush call/module enable event to update service container.');

        module_disable(array('at_test'));
        try {
            $test_service = at()->get('at_test');
        } catch (Exception $e) {
            $this->assertEqual('Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException', $e, 'Listen to disable disable module event to update service container.');
        }
    }

    public function checkYamlServices()
    {
        // Yaml parser
        $parser = at()->get('yaml.parser');
        $services = $parser->parse(file_get_contents(drupal_get_path('module', 'at') . '/at.services.yml'));
        $this->assertTrue('Symfony\Component\Yaml\Parser' === get_class($parser));
        $this->assertEqual('Symfony\Component\Yaml\Parser', $services['services']['yaml.parser']['class']);

        // Yaml dumper
        $dumper = at()->get('yaml.dumper');
        $this->assertEqual('[1, 2, 3]', $dumper->dump([1, 2, 3]));
    }
}
