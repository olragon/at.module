<?php

namespace Drupal\at\Tests\Traits;

use Drupal\at_test\KeyValueStorage\RoutingItemFixture;

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
        $created_item = $em->find('Drupal\at_test\KeyValueStorage\RoutingItemFixture', ['name' => 'demo']);
        $this->assertTrue($created_item instanceof RoutingItemFixture);
        $this->assertEqual('Demo route item', $created_item->getTitle());
    }

}
