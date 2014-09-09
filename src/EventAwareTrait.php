<?php

namespace AndyTruong\Event;

use ArrayAccess;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\Event as BaseEvent;

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
     * Check available of dispatcher.
     *
     * @return boolean
     */
    public function hasDispatcher()
    {
        return null !== $this->dispatcher;
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
     * @param string $eventName
     * @param BaseEvent $event
     * @return BaseEvent
     */
    public function dispatch($eventName, BaseEvent $event = null)
    {
        return $this->getDispatcher()->dispatch($eventName, $event);
    }

    /**
     * Dispatch an event with extra params.
     *
     * @param string $eventName
     * @param string|object $subject
     * @param array|ArrayAccess $params
     * @return Event
     */
    public function trigger($eventName, &$subject, $params = [])
    {
        $event = new Event($subject, $params);
        $return = $this->getDispatcher()->dispatch($eventName, $event);
        if (!is_object($subject)) {
            $subject = $event->getSubject();
        }
        return $return;
    }

    /**
     * Collect results from listeners.
     *
     * @param string $eventName
     * @param string|object $subject
     * @param array $params
     * @param array $validators
     * @return array
     */
    public function collectResults($eventName, $subject, $params = [], array $validators = [])
    {
        $event = new Event($subject, $params);
        foreach ($validators as $validator) {
            $event->addResultValidator($validator);
        }
        return $this->getDispatcher()->dispatch($eventName, $event)->getResults();
    }

}
