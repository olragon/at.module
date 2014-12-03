<?php

namespace Drupal\at\Tests;

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
