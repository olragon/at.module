<?php

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
    return at()->getHookImplementations()->getHookMenu()->execute();
}
