<?php

namespace Drupal\at\Tests\Fixtures;

class SystemDemoController
{

    public function render()
    {
        return [
            '#prefix' => '<div id="system-demo" class="system-demo">',
            '#markup' => 'System demo body.',
            '#suffix' => '</div>',
        ];
    }

}
