<?php

use Drupal\at\Exceptions\RouteNotFoundException;

/**
 * Access callback for all routes.
 * @param string $route_name
 * @return bool
 */
function at_page_access_callback($route_name)
{
    if ($route = at_route_load($route_name)) {
        return entity_access('view', 'at_route', $route);
    }
    throw new RouteNotFoundException();
}

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
