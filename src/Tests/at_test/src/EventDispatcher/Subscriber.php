<?php

namespace Drupal\at_test\EventDispatcher;

class Subscriber
{

    public function onSay(Event $e)
    {
        variable_set('at_test_on_say', $e->getMessage());
    }

}
