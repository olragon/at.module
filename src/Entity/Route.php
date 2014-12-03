<?php

namespace Drupal\at\Entity;

use Entity;

class Route extends Entity
{

    /** @var int  */
    public $id;

    /** @var string */
    public $bundle;

    /** @var string */
    public $name;

    /** @var string */
    public $title;

    /** @var mixed[] */
    public $data;

    /** @var int */
    public $status;

    /** @var string */
    public $module;

}
