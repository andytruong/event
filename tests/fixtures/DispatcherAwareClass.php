<?php

namespace AndyTruong\Event\Fixtures;

use AndyTruong\Event\DispatcherAwareInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DispatcherAwareClass implements DispatcherAwareInterface
{

    private $dispatcher;

    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    public function setDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

}
