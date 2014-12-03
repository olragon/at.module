<?php

namespace Drupal\at\Tests;

use Doctrine\KeyValueStore\Mapping\Annotations as KeyValue;

trait KeyValueStorageTestCaseTrait
{

    public function checkKeyValueStorage()
    {
        $this->doCheckKeyValueStorage('default');
        $this->doCheckKeyValueStorage('array');
    }

    public function doCheckKeyValueStorage($engine)
    {
        $em = at()->getKeyValueStorageEntityManager($engine);

        // Create
        $item = new RoutingItemFixture('demo', 'Demo route item');
        $em->persist($item);
        $em->flush();

        // Read
        $created_item = $em->find('Drupal\at\Tests\RoutingItemFixture', ['name' => 'demo']);
        $this->assertTrue($created_item instanceof RoutingItemFixture);
        $this->assertEqual('Demo route item', $created_item->getTitle());
    }

}

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
