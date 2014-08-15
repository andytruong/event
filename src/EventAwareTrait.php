<?php

namespace AndyTruong\Event;

use ArrayAccess;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * This Trait is only available when we use this library with symfony/event-dispatcher:~2.5.0.
 *
 * Example:
 *
 *  class MyClass {
 *      use \AndyTruong\Common\Traits\EventAwareTrait;
 *
 *      public function myEventAwareMethod() {
 *          $this->dispatch('my.event.before');
 *          $event = new \Symfony\Component\EventDispatcher\Event();
 *          $this->dispatch('my.event.after', $event);
 *      }
 *  }
 */
trait EventAwareTrait
{

    protected $dispatcher;

    /**
     * Inject dispatcher.
     *
     * @param EventDispatcherInterface $dispatcher
     */
    public function setDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Get dispatcher.
     *
     * @return EventDispatcherInterface
     */
    public function getDispatcher()
    {
        if (null === $this->dispatcher) {
            $this->setDispatcher($this->getDefaultDispatcher());
        }

        return $this->dispatcher;
    }

    /**
     * Generate default dispatcher, override this if your class would like to
     * provide an other default dispatcher.
     *
     * @return EventDispatcher
     */
    protected function getDefaultDispatcher()
    {
        return new EventDispatcher();
    }

    /**
     * Shortcut to dispatch an event.
     *
     * @param string $event_name
     * @param Event $event
     * @return Event
     */
    public function dispatch($event_name, Event $event = null)
    {
        return $this->getDispatcher()->dispatch($event_name, $event);
    }

    /**
     * Dispatch an event with extra params.
     *
     * @param string $event_name
     * @param string|object $target
     * @param array|ArrayAccess $params
     * @return Event
     */
    public function trigger($event_name, $target = null, $params = [])
    {
        $event = new Event($event_name, $target, $params);
        return $this->getDispatcher()->dispatch($event_name, $event);
    }

    /**
     * Collect results from listeners.
     *
     * @param string $event_name
     * @param string|object $target
     * @param array $params
     * @param array $validators
     * @return array
     */
    public function collectResults($event_name, $target = null, $params = [], array $validators = [])
    {
        $event = new Event($event_name, $target, $params);
        foreach ($validators as $validator) {
            $event->addResultValidator($validator);
        }
        return $this->getDispatcher()->dispatch($event_name, $event)->getResults();
    }

}
