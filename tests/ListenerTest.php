<?php

/**
 * TOBENTO
 *
 * @copyright   Tobias Strub, TOBENTO
 * @license     MIT License, see LICENSE file distributed with this source code.
 * @author      Tobias Strub
 * @link        https://www.tobento.ch
 */

declare(strict_types=1);

namespace Tobento\Service\Event\Test;

use PHPUnit\Framework\TestCase;
use Tobento\Service\Event\Listener;
use Tobento\Service\Event\ListenerInterface;
use Tobento\Service\Event\CallableFactory;
use Tobento\Service\Event\Test\Mock\FooListener;
use Tobento\Service\Event\Test\Mock\FooEvent;

/**
 * ListenerTest
 */
class ListenerTest extends TestCase
{
    public function testThatImplementsListenerInterface()
    {
        $this->assertInstanceof(
            ListenerInterface::class,
            new Listener(FooListener::class, [])
        );
    }
    
    public function testGetListenerMethod()
    {
        $listener = new Listener(FooListener::class, []);
        
        $this->assertSame(
            FooListener::class,
            $listener->getListener()
        );
    }
    
    public function testGetListenerEventsMethod()
    {
        $listener = new Listener(new FooListener(), ['events']);
        
        $this->assertSame(
            ['events'],
            $listener->getListenerEvents()
        );
    }
    
    public function testGetForEventMethod()
    {
        $obj = new FooListener();
        
        $listener = new Listener($obj, [
            FooEvent::class => [
                [$obj, 'foo'],
            ],
        ]);
        
        $iterator = $listener->getForEvent(new FooEvent(), new CallableFactory());
        
        $callables = iterator_to_array($iterator, false);
        
        $this->assertSame(
            [
                [$obj, 'foo'],
            ],
            $callables
        );
    }
    
    public function testGetForEventMethodSpecificOnly()
    {
        $obj = new FooListener();
        
        $listener = (new Listener($obj, [
            FooEvent::class => [
                [$obj, 'foo'],
            ],
        ]))->event(FooEvent::class);
        
        $iterator = $listener->getForEvent(new FooEvent(), new CallableFactory());
        
        $callables = iterator_to_array($iterator, false);
        
        $this->assertSame(
            [
                [$obj, 'foo'],
            ],
            $callables
        );
    }
    
    public function testGetForEventMethodSpecificOnlyReturnsNone()
    {
        $obj = new FooListener();
        
        $listener = (new Listener($obj, [
            FooEvent::class => [
                [$obj, 'foo'],
            ],
        ]))->event('AnotherEventOnly');
        
        $iterator = $listener->getForEvent(new FooEvent(), new CallableFactory());
        
        $callables = iterator_to_array($iterator, false);
        
        $this->assertSame(
            [],
            $callables
        );
    }
    
    public function testPriorityMethods()
    {
        $listener = new Listener(new FooListener(), ['events']);
        $listener->priority(2000);
        
        $this->assertSame(
            2000,
            $listener->getPriority()
        );
    }    
}