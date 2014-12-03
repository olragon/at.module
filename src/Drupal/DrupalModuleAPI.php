<?php

namespace Drupal\at\Drupal;

class DrupalModuleAPI
{

    public function getPath($type, $name)
    {
        return drupal_get_path($type, $name);
    }

}
