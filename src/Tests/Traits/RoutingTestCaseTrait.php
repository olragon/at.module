<?php

namespace Drupal\at\Tests\Traits;

trait RoutingTestCaseTrait
{

    public function checkDefaultRouteTypeIsCreated()
    {
        /* @var $this \DrupalWebTestCase */
        $this->assertNotNull(entity_load('at_route_type', FALSE, ['type' => 'route']), 'Default route type is created');
    }

}
