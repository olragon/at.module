<?php

namespace Drupal\at\Entity;

use Entity;

class RouteType extends Entity
{

    /** @var int */
    public $id;

    /** @var string */
    public $type;

    /** @var string */
    public $label;

    /** @var int */
    public $weight;

    /** @var int */
    public $status;

    /** @var string */
    public $module;

    /** @var mixed[] */
    public $data;

}
