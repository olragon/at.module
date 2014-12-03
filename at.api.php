<?php

/**
 * Implements hook_at_container_extension_info().
 * @returnarray
 */
function hook_at_container_extension_info()
{
    return [
        'at_base' => [
            'class'     => 'Drupal\at\Container\CoreExtension',
            'arguments' => [],
        ],
    ];
}
