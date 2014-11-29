<?php

namespace Drupal\at\Container;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class CoreExtension extends Extension
{

    public function load(array $config, ContainerBuilder $container)
    {
        foreach (['at' => 'at'] + at_modules('at', 'services') as $module) {
            $locator = new FileLocator(DRUPAL_ROOT);
            $loader = new YamlFileLoader($container, $locator);
            $loader->load(drupal_get_path('module', $module) . '/' . $module . '.services.yml');
        }
    }

}
