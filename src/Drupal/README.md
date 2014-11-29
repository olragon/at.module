Drupal\at\Drupal
====

Instead of use Drupal's API directly, we wrap them with classes, which is easier
for testing.

For example: node_load($nid) always perform SQL queries, wrap it, we can skip
unwanted actions:

```php
<?php

class MyClass {
    public function __construct($nodeApi) { /* … */}

    public function doThing() {
        $this->nodeApi->delete(…);
    }
}

?>
