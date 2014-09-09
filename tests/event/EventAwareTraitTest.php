<?php

namespace AndyTruong\Event\TestCases\Traits;

use AndyTruong\Event\Event as CustomEvent;
use AndyTruong\Event\Fixtures\Traits\EventAwareClass;
use PHPUnit_Framework_TestCase;
use RuntimeException;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @group event-trait
 */
class EventAwareTraitTest extends PHPUnit_Framework_TestCase
{

    public function testSetter()
    {
        $obj = new EventAwareClass();
        $this->assertFalse($obj->hasDispatcher());
        $obj->setDispatcher($dispatcher = new EventDispatcher());
        $this->assertTrue($obj->hasDispatcher());
        $this->assertSame($dispatcher, $obj->getDispatcher());
    }

    public function testGetDefaultDispatcher()
    {
        $obj = new EventAwareClass();
        $this->assertInstanceOf('Symfony\Component\EventDispatcher\EventDispatcherInterface', $obj->getDispatcher());
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Event is executed.
     */
    public function testDispatchShortcut()
    {
        $obj = new EventAwareClass();
        $obj->getDispatcher()->addListener('my_event', function(Event $event) {
            throw new RuntimeException('Event is executed.');
        });
        $obj->dispatch('my_event');
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Event is executed. Class: AndyTruong\Event\Fixtures\Traits\EventAwareClass. Params: param_1, param_2
     */
    public function testDispatchCustomEvent()
    {
        $obj = new EventAwareClass();
        $obj->getDispatcher()->addListener('my_event', function(CustomEvent $event) {
            $msg = sprintf(
                'Event is executed. Class: %s. Params: %s'
                , get_class($event->getSubject())
                , implode(', ', $event->getArguments())
            );
            throw new RuntimeException($msg);
        });
        $event = new CustomEvent($obj, array('param_1', 'param_2'));
        $obj->dispatch('my_event', $event);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Event is executed. Class: AndyTruong\Event\Fixtures\Traits\EventAwareClass. Params: param_1, param_2
     */
    public function testTriggerShortcut()
    {
        $obj = new EventAwareClass();
        $obj->getDispatcher()->addListener('my_event', function(CustomEvent $event) {
            $event->getSubject();
            $msg = sprintf(
                'Event is executed. Class: %s. Params: %s'
                , get_class($event->getSubject())
                , implode(', ', $event->getArguments())
            );
            throw new RuntimeException($msg);
        });
        $obj->trigger('my_event', $obj, array('param_1', 'param_2'));
    }

    /**
     * @group ondev
     * @dataProvider sourceCollectResults
     */
    public function testCollectResults($add_result, $exception = null, $msg = null)
    {
        if (null !== $exception) {
            $this->setExpectedException($exception, $msg);
        }

        $obj = new EventAwareClass();
        $obj
            ->getDispatcher()
            ->addListener('my_collection_event', function(CustomEvent $event) use ($add_result) {
                /* @var $event \CustomEvent  */
                $event->addResultValidator(function($input) {
                    if (!is_numeric($input)) {
                        throw new \Exception('Only number is allowed.');
                    }

                    if ($input % 2 !== 0) {
                        throw new \Exception('Only even number is allowed.');
                    }
                });

                $event->addResult($add_result);
            });

        $obj->trigger('my_collection_event', $obj, ['param_1', 'param_2']);
    }

    public function sourceCollectResults()
    {
        return [
            ['string', 'RuntimeException', 'Only number is allowed.'],
            [1, 'Exception', 'Only even number is allowed.'],
            [2],
        ];
    }

}
