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
 * Implements hook_flush_caches().
 */
function at_flush_caches()
{
    at()->flushContainer();
}

/**
 * Implements hook_modules_enabled().
 */
function at_modules_enabled()
{
    at()->flushContainer();
}

/**
 * Implements hook_modules_disabled().
 */
function at_modules_disabled()
{
    at()->flushContainer();
}
