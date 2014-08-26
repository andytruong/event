<?php

namespace AndyTruong\Event;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

interface DispatcherAwareInterface
{

    /**
     * Inject dispatcher.
     * 
     * @param EventDispatcherInterface $dispatcher
     */
    public function setDispatcher(EventDispatcherInterface $dispatcher);

    /**
     * Get event event dispatcher.
     *
     * @return EventDispatcherInterface
     */
    public function getDispatcher();
}
