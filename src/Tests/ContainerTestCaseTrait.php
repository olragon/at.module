<?php

namespace Drupal\at\Tests;

trait ContainerTestCaseTrait
{

    private function getServiceContainer()
    {
        return at()->getContainer();
    }

    public function checkServiceContainer()
    {
        /* @var $testCase \DrupalWebTestCase */
        $testCase = $this;

        $container = $this->getServiceContainer();
        $testCase->assertEqual('AT_Container', get_class($container));
        $testCase->assertTrue(method_exists($container, 'getServiceIds'));

        // Get expression language service
        $expLang = $container->get('expression_language');
        $testCase->assertEqual('Symfony\Component\ExpressionLanguage\ExpressionLanguage', get_class($expLang));
    }

    public function checkYamlServices()
    {
        $container = $this->getServiceContainer();

        // Yaml parser
        $parser = $container->get('yaml.parser');
        $services = $parser->parse(file_get_contents(drupal_get_path('module', 'at') . '/at.services.yml'));
        $this->assertTrue('Symfony\Component\Yaml\Parser' === get_class($parser));
        $this->assertEqual('Symfony\Component\Yaml\Parser', $services['services']['yaml.parser']['class']);

        // Yaml dumper
        $dumper = $container->get('yaml.dumper');
        $this->assertEqual('[1, 2, 3]', $dumper->dump([1, 2, 3]));
    }

}
