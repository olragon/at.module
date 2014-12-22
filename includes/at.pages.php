<?php

use Drupal\at\Exceptions\RouteNotFoundException;

/**
 * Page callback for all routes.
 * @param string $route_name
 * @return array|string
 */
function at_page_callback($route_name)
{
    if ($route = at_route_load($route_name)) {
        return entity_view('at_route', [$route], 'full', NULL, TRUE);
    }
    throw new RouteNotFoundException();
}
