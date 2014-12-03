<?php

namespace Drupal\at\Tests\Traits;

use Drupal\at\Entity\Route;

trait RoutingTestCaseTrait
{

    public function checkDefaultRouteTypeIsCreated()
    {
        $this->assertNotNull(entity_load('at_route_type', FALSE, ['type' => 'route']), 'Default route type is created');
    }

    public function checkRouteDefinition()
    {
        $this->verbose(json_encode(at_menu()));
        $route = at_route_load('system.demo');
        $this->assertTrue($route instanceof Route);

        // Fetch the page
        $this->drupalGet('system/demo');
        $this->assertTitle('System demo');
        $this->assertFieldById('system-demo');
    }

}
