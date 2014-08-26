<?php

namespace AndyTruong\Event\TestCases;

use AndyTruong\Event\Fixtures\DispatcherAwareClass;
use PHPUnit_Framework_TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

class DispatcherAwareTest extends PHPUnit_Framework_TestCase
{

    public function testSetGet()
    {
        $obj = new DispatcherAwareClass();
        $this->assertNull($obj->getDispatcher());
        $obj->setDispatcher(new EventDispatcher());
        $this->assertInstanceOf('Symfony\Component\EventDispatcher\EventDispatcherInterface', $obj->getDispatcher());
    }

}
