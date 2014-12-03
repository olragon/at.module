<?php

namespace Drupal\at_base\Tests\DrupalAPI;

use Drupal\at\Drupal\DrupalModuleAPI as BaseAPI;

class DrupalModuleAPI extends BaseAPI
{

    public function getPath($type, $name)
    {
        if ('system' === $name) {
            return AT_ROOT . '/misc/fixtures/system/';
        }
        return parent::getPath($type, $name);
    }

}
