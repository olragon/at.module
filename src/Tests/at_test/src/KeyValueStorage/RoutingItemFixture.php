<?php

namespace Drupal\at_test\KeyValueStorage;

use Doctrine\KeyValueStore\Mapping\Annotations as KeyValue;

/**
 * @KeyValue\Entity(storageName="demo_routing_item")
 */
class RoutingItemFixture
{

    /** @KeyValue\Id */
    private $name;
    private $title;

    public function __construct($name, $title)
    {
        $this->name = $name;
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

}
