<?php

/**
 * Implementshook_permission().
 */
function at_permission()
{
    return [
        'administer route types' => [
            'title'       => 'Administer route types',
            'description' => 'Configure route type entities',
        ],
    ];
}

/**
 * Implements at.module's hook_at_container_extension_info()
 */
function at_at_container_extension_info()
{
    return [
        'at_base' => [
            'class'     => 'Drupal\at\Container\CoreExtension',
            'arguments' => [],
        ],
    ];
}

/**
 * Implements core's hook_menu().
 */
function at_menu()
{
    if (at()->isContainerCreatable()) {
        return at()->getHookImplementations()->getHookMenu()->execute();
    }
}

/**
 * Implements hook_entity_info().
 */
function at_entity_info()
{
    $info = [];

    $info['at_route_type'] = [
        'label'            => 'Route type',
        'plural label'     => 'Route types',
        'description'      => 'Types of route items',
        'entity class'     => 'Drupal\at\Entity\RouteType',
        'controller class' => 'Drupal\at\Entity\RouteTypeController',
        'base table'       => 'at_route_type',
        'fieldable'        => FALSE,
        'bundle of'        => 'at_route',
        'exportable'       => TRUE,
        'entity keys'      => array('id' => 'id', 'name' => 'type', 'label' => 'label'),
        'access callback'  => 'at_route_type_access_callback',
        'module'           => 'at',
        'admin ui'         => [
            'path'             => 'admin/structure/route-types',
            'file'             => 'includes/at.pages.php',
            'controller class' => 'Drupal\at\Entity\RouteTypeUIController',
        ],
    ];

    $info['at_route'] = [
        'label'                         => 'Route item',
        'description'                   => 'Store structure of route items',
        'entity class'                  => 'Drupal\at\Entity\Route',
        'controller class'              => 'Drupal\at\Entity\RouteController',
        'extra fields controller class' => 'Drupal\at\Entity\RouteExtraFieldsController',
        'base table'                    => 'at_route',
        'fieldable'                     => TRUE,
        'exportable'                    => TRUE,
        'entity keys'                   => array('id' => 'id', 'bundle' => 'bundle', 'name' => 'name', 'label' => 'title'),
        'bundle keys'                   => array('bundle' => 'bundle'),
        'access callback'               => 'at_route_access_callback',
        'module'                        => 'at',
        'bundles'                       => [],
        'admin ui'                      => [
            'path'             => 'admin/structure/at-routes',
            'file'             => 'includes/at.pages.php',
            'controller class' => 'Drupal\at\Entity\RouteUIController',
        ],
    ];

    // Add bundle info but bypass entity_load() as we cannot use it here.
    foreach (db_select('at_route_type', 'route_type')->fields('route_type')->execute()->fetchAllAssoc('type') as $name => $route_type) {
        $info['at_route']['bundles'][$name] = [
            'label' => $route_type->label,
            'admin' => [
                'path'             => 'admin/structure/route-types/manage/%at_route_type',
                'real path'        => 'admin/structure/route-types/manage/' . $name,
                'bundle argument'  => 4,
                'access arguments' => array('administer route types'),
            ],
        ];
    }

    return $info;
}
