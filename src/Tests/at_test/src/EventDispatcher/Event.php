<?php

namespace Drupal\at_test\EventDispatcher;

use Symfony\Component\EventDispatcher\Event as BaseEvent;

class Event extends BaseEvent
{

    private $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }

}
