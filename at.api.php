<?php

function hook_at_container_extension_info()
{
    return [
        'at_base' => [
            'class'     => 'Drupal\at\Container\CoreExtension',
            'arguments' => [],
        ],
    ];
}

/**
 * Hook fired when new dispatcher is created.
 *
 * @param Symfony\Components\EventDispatcher\EventDispatcher $dispatcher
 */
function hook_at_init_dispatcher($dispatcher)
{
    $dispatcher->addListener('foo.event', function($event) {
        watchdog('foo.event', print_r($event, TRUE));
    });
}
