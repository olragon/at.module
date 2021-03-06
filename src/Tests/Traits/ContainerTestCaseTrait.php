<?php

namespace Drupal\at\Tests\Traits;

use Drupal\at_test\ATTest;

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

    /**
     * Make sure the service containers is updated when modules are enabled/disabled.
     */
    public function checkServiceContainerWhenModuleOnOff()
    {
        // Case: Enable module
        $this->assertEqual(at()->get('at_test') instanceof ATTest, 'Enable module, new service should be detected.');

        // Case: Disable module
        module_disable(array('at_test'));
        $msg = 'Module disabled, the related service should be no longer part of service container.';
        $this->doEval("try { at()->get('at_test'); } catch (Exception \$e) { echo 'at_test Service not found'; }");
        $this->assertText('at_test Service not found', $msg);

        // Reset default module state, at_test module is still needed for other
        // test cases.
        module_enable(array('at_test'));
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
