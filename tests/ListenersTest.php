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
use Tobento\Service\Event\Listeners;
use Tobento\Service\Event\ListenersInterface;
use Tobento\Service\Event\Listener;
use Tobento\Service\Event\CallableFactoryInterface;
use Tobento\Service\Event\ListenerEventsResolverInterface;
use Tobento\Service\Event\InvalidCallableException;
use Psr\EventDispatcher\ListenerProviderInterface;
use Tobento\Service\Event\Test\Mock\FooListener;
use Tobento\Service\Event\Test\Mock\FooBarListener;
use Tobento\Service\Event\Test\Mock\StaticFooListener;
use Tobento\Service\Event\Test\Mock\InvokableFooListener;
use Tobento\Service\Event\Test\Mock\FooEvent;
use Tobento\Service\Event\Test\Mock\BarEvent;

/**
 * ListenersTest
 */
class ListenersTest extends TestCase
{
    public function testThatImplementsListenerProviderInterface()
    {
        $this->assertInstanceof(
            ListenerProviderInterface::class,
            new Listeners()
        );
    }
    
    public function testThatImplementsListenersInterface()
    {
        $this->assertInstanceof(
            ListenersInterface::class,
            new Listeners()
        );
    }    
    
    public function testAddMethodReturnsListener()
    {
        $listeners = new Listeners();
        
        $listener = $listeners->add(FooListener::class);

        $this->assertInstanceof(
            Listener::class,
            $listener
        );
    }
    
    public function testAddListenerMethodReturnsListenersInterface()
    {
        $listeners = new Listeners();
        
        $listener = $listeners->addListener(new Listener(FooListener::class, []));

        $this->assertInstanceof(
            ListenersInterface::class,
            $listener
        );
    }
    
    public function testAllMethod()
    {
        $listeners = new Listeners();
        
        $listener = $listeners->add(FooListener::class);
        
        $listener1 = new Listener(FooListener::class, []);
        $listeners->addListener($listener1);

        $this->assertSame(
            [
                $listener,
                $listener1,
            ],
            $listeners->all()
        );
    }
    
    public function testListenersForEventMethodThrowsInvalidCallableException()
    {
        $this->expectException(InvalidCallableException::class);
        
        $listeners = new Listeners();
        
        $listener = $listeners->add(FooListener::class);
        
        $listeners->getListenersForEvent(new FooEvent())->rewind();
    }
    
    public function testAddMethodWithObject()
    {
        $listeners = new Listeners();
        
        $listener = $listeners->add(new FooListener());
        
        $iterator = $listeners->getListenersForEvent(new FooEvent());
        
        $listeners = iterator_to_array($iterator, false);
        
        $this->assertTrue(is_callable($listeners[0]));
    }
    
    public function testAddMethodWithCallableObject()
    {
        $listeners = new Listeners();
        
        $listener = $listeners->add([new FooListener(), 'foo']);
        
        $iterator = $listeners->getListenersForEvent(new FooEvent());
        
        $listeners = iterator_to_array($iterator, false);
        
        $this->assertTrue(is_callable($listeners[0]));
    }
    
    public function testAddMethodWithCallableArray()
    {
        $listeners = new Listeners();
        
        $listener = $listeners->add([StaticFooListener::class, 'foo']);
        
        $iterator = $listeners->getListenersForEvent(new FooEvent());
        
        $listeners = iterator_to_array($iterator, false);
        
        $this->assertTrue(is_callable($listeners[0]));
    }
    
    public function testAddMethodWithCallableFunction()
    {
        $listeners = new Listeners();
        
        $listener = $listeners->add('Tobento\Service\Event\Test\handleFoo');
        
        $iterator = $listeners->getListenersForEvent(new FooEvent());
        
        $listeners = iterator_to_array($iterator, false);
        
        $this->assertTrue(is_callable($listeners[0]));
    }
    
    public function testAddMethodWithClosure()
    {
        $listeners = new Listeners();
        
        $listener = $listeners->add(function(FooEvent $event) {
            //
        });
        
        $iterator = $listeners->getListenersForEvent(new FooEvent());
        
        $listeners = iterator_to_array($iterator, false);
        
        $this->assertTrue(is_callable($listeners[0]));
    }
    
    public function testAddMethodWithInvokable()
    {
        $listeners = new Listeners();
        
        $listener = $listeners->add(new InvokableFooListener());
        
        $iterator = $listeners->getListenersForEvent(new FooEvent());
        
        $listeners = iterator_to_array($iterator, false);
        
        $this->assertTrue(is_callable($listeners[0]));
    }
    
    public function testAddMethodWithMultipleEvents()
    {
        $listeners = new Listeners();
        
        $listener = $listeners->add(new FooBarListener());
        
        $iterator = $listeners->getListenersForEvent(new FooEvent());
        
        $this->assertCount(2, $iterator);
        
        $iterator = $listeners->getListenersForEvent(new BarEvent());
        
        $this->assertCount(2, $iterator);
    }
    
    public function testPriority()
    {
        $listeners = new Listeners();
        
        $listener = $listeners->add(new FooListener());
        
        $listener1 = $listeners->add('Tobento\Service\Event\Test\handleFoo');
        
        $iterator = $listeners->getListenersForEvent(new FooEvent());
        
        $listeners = iterator_to_array($iterator, false);
        
        $this->assertSame(
            'Tobento\Service\Event\Test\handleFoo', 
            $listeners[1]
        );
        
        $listeners = new Listeners();
        
        $listener = $listeners->add(new FooListener())->priority(2000);
        
        $listener1 = $listeners->add('Tobento\Service\Event\Test\handleFoo')->priority(3000);
        
        $iterator = $listeners->getListenersForEvent(new FooEvent());
        
        $listeners = iterator_to_array($iterator, false);
        
        $this->assertSame(
            'Tobento\Service\Event\Test\handleFoo', 
            $listeners[0]
        );
    }
    
    public function testSpecificEvent()
    {
        $listeners = new Listeners();
        
        $listener = $listeners->add(new FooBarListener());
        
        $iterator = $listeners->getListenersForEvent(new FooEvent());
        
        $this->assertCount(2, $iterator);
        
        $iterator = $listeners->getListenersForEvent(new BarEvent());
        
        $this->assertCount(2, $iterator);
        
        $listeners = new Listeners();
        
        $listener = $listeners->add(new FooBarListener())->event(FooEvent::class);
        
        $iterator = $listeners->getListenersForEvent(new FooEvent());
        
        $this->assertCount(2, $iterator);
        
        $iterator = $listeners->getListenersForEvent(new BarEvent());
        
        $this->assertCount(0, $iterator);        
    }     
}

function handleFoo(FooEvent $event): void
{
    // do nothing
}