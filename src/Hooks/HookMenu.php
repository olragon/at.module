<?php

namespace Drupal\at\Hooks;

use Drupal\at\Drupal\DrupalModuleAPI;
use Drupal\at\JsonSchemaValidator;

class HookMenu
{

    /** @var \Symfony\Component\Yaml\Parser */
    private $yamlParser;

    /** @var JsonSchemaValidator */
    private $valiator;

    /** @var string */
    private $schemaUri;

    /** @var DrupalModuleAPI */
    private $moduleAPI;

    public function __construct($parser, JsonSchemaValidator $validator, $moduleAPI)
    {
        $this->yamlParser = $parser;
        $this->valiator = $validator;
        $this->moduleAPI = $moduleAPI;
        $this->schemaUri = AT_ROOT . '/misc/schema/routing.json';
    }

    public function execute()
    {
        $items = [];
        foreach (at_modules('at') as $module) {
            $file = DRUPAL_ROOT . '/' . $this->moduleAPI->getPath('module', $module) . '/' . $module . '.routing.yml';
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

    private function convertRoutingItemToDrupal7Style($module, $name, array $input)
    {
        $menu_item = [
            'title'            => isset($input['defaults']['_title']) ? $input['defaults']['_title'] : '',
            'type'             => isset($input['defaults']['_type']) ? $input['defaults']['_type'] : MENU_NORMAL_ITEM,
            'access callback'  => 'at_page_access_callback',
            'access arguments' => [$name],
            'page callback'    => 'at_page_callback',
            'page arguments'   => [$name],
            'file'             => 'includes/at.pages.php',
        ];

        if ($route = entity_load('at_route', FALSE, ['name' => $name])) {
            $route = entity_create('at_route', [
                'type' => isset($input['bundle']) ? $input['bundle'] : 'route',
                'name' => $name,
            ]);
        }

        $route->data = ['module' => $module] + $input;

        return $menu_item;
    }

}
