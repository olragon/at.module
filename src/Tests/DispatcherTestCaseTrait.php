<?php

namespace Drupal\at\Tests;

use Drupal\at_test\EventDispatcher\Event;
use Drupal\at_test\EventDispatcher\Subscriber;
use Symfony\Component\EventDispatcher\EventDispatcher;

trait DispatcherTestCaseTrait
{

    public function checkGetDispatcher()
    {
        $this->assertTrue(at()->getDispatcher() instanceof EventDispatcher);
        $this->assertTrue(at()->get('dispatcher') instanceof EventDispatcher);
        $this->assertTrue(at()->createDispatcher() instanceof EventDispatcher);
    }

    /**
     * Test case to check that the factory method is used to create new dispatcher.
     *
     * Steps:
     * - set at_test_at_init_dispatcher to FALSE.
     * - in module at_test, we implements hook_at_init_dispatcher and update
     *   value of at_test_at_init_dispatcher to microtime which hook is fired.
     */
    public function checkFactoryIsCalledWhenCreateDispatcher()
    {
        variable_set('at_test_at_init_dispatcher', FALSE);
        at()->getDispatcher();
        $result = variable_get('at_test_at_init_dispatcher');
        $this->assertTRUE($result, 'When dispatcher was created, factory is called and hook_at_init_dispatcher is fired');
    }

    /**
     * Try a very simple event-dispatching usage. You can use this as example.
     *
     * @tag examples
     */
    public function checkListenerSubscriber()
    {
        // Create objects
        $dispatcher = at()->getDispatcher();
        $listener = new Subscriber();
        $event = new Event($preMsg = 'Hello');

        // Setup the dispatcher & default states
        variable_set('at_test_on_say', NULL);
        $dispatcher->addListener('say', array($listener, 'onSay'));

        // Action: Dispatch event
        $dispatcher->dispatch('say', $event);

        // Checking result: Get variable to compare
        $postMsg = variable_get('at_test_on_say');
        $this->assertEqual($preMsg, $postMsg, 'Dispatcher should dispatch message to listener.');
    }

}
