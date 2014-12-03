<?php

namespace Drupal\at\Hooks;

use Drupal\at\JsonSchemaValidator;

class HookMenu
{

    /** @var \Symfony\Component\Yaml\Parser */
    private $yamlParser;

    /** @var JsonSchemaValidator */
    private $valiator;

    /** @var string */
    private $schemaUri;

    public function __construct($parser, JsonSchemaValidator $validator)
    {
        $this->yamlParser = $parser;
        $this->valiator = $validator;
        $this->schemaUri = AT_ROOT . '/misc/schema/routing.json';
    }

    public function execute()
    {
        $items = [];
        foreach (at_modules('at') as $module) {
            $file = DRUPAL_ROOT . '/' . drupal_get_path('module', $module) . '/' . $module . '.routing.yml';
            if (file_exists($file)) {
                $routes = $this->yamlParser->parse(file_get_contents($file));
                $items += $this->getMenuItems($module, $routes);
            }
        }
        return $items;
    }

    private function getMenuItems($module, $routes)
    {
        $items = [];
        foreach ($routes as $name => $route) {
            if ($this->valiator->validate($route, $this->schemaUri)) {
                $items[$name] = $this->convertRoutingItemToDrupal7Style($module, $name, $route);
            }
        }
        return $items;
    }

    private function convertRoutingItemToDrupal7Style($module, $name, $route)
    {
        return [
            'title'            => '…',
            # 'title callback'   => 't',
            # 'title arguments'  => array(1),
            'access callback'  => '',
            'access arguments' => [],
            'page callback'    => '',
            'page arguments'   => [],
            'file path'        => drupal_get_path('module', $module),
            'file'             => '…',
        ];
    }

}
