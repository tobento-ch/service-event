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
use Tobento\Service\Event\Listener;
use Tobento\Service\Event\InvalidCallableException;
use Tobento\Service\Event\AutowiringCallableFactory;
use Tobento\Service\Event\Test\Mock\FooListener;
use Tobento\Service\Event\Test\Mock\FooBarListener;
use Tobento\Service\Event\Test\Mock\StaticFooListener;
use Tobento\Service\Event\Test\Mock\InvokableFooListener;
use Tobento\Service\Event\Test\Mock\FooEvent;
use Tobento\Service\Event\Test\Mock\BarEvent;
use Tobento\Service\Event\Test\Mock\ListenerWithBuildInParameter;
use Tobento\Service\Container\Container;

/**
 * ListenersAutowiringTest
 */
class ListenersAutowiringTest extends TestCase
{    
    public function testAddMethodWithObject()
    {
        $listeners = new Listeners(
            new AutowiringCallableFactory(new Container())
        );
        
        $listener = $listeners->add(new FooListener());
        
        $iterator = $listeners->getListenersForEvent(new FooEvent());
        
        $listeners = iterator_to_array($iterator, false);
        
        $this->assertTrue(is_callable($listeners[0]));
    }
    
    public function testAddMethodWithObjectString()
    {
        $listeners = new Listeners(
            new AutowiringCallableFactory(new Container())
        );
        
        $listener = $listeners->add(FooListener::class);
        
        $iterator = $listeners->getListenersForEvent(new FooEvent());
        
        $listeners = iterator_to_array($iterator, false);
        
        $this->assertTrue(is_callable($listeners[0]));
    }    
    
    public function testAddMethodWithCallableObject()
    {
        $listeners = new Listeners(
            new AutowiringCallableFactory(new Container())
        );
        
        $listener = $listeners->add([new FooListener(), 'foo']);
        
        $iterator = $listeners->getListenersForEvent(new FooEvent());
        
        $listeners = iterator_to_array($iterator, false);
        
        $this->assertTrue(is_callable($listeners[0]));
    }  
    
    public function testAddMethodWithCallableArray()
    {
        $listeners = new Listeners(
            new AutowiringCallableFactory(new Container())
        );
        
        $listener = $listeners->add([StaticFooListener::class, 'foo']);
        
        $iterator = $listeners->getListenersForEvent(new FooEvent());
        
        $listeners = iterator_to_array($iterator, false);
        
        $this->assertTrue(is_callable($listeners[0]));
    }
    
    public function testAddMethodWithBuildInParameters()
    {
        $listeners = new Listeners(
            new AutowiringCallableFactory(new Container())
        );
        
        $listener = $listeners->add([ListenerWithBuildInParameter::class, ['number' => 5]]);
        
        $iterator = $listeners->getListenersForEvent(new FooEvent());
        
        $listeners = iterator_to_array($iterator, false);
        
        $this->assertTrue(is_callable($listeners[0]));
    }    
    
    public function testAddMethodWithCallableFunction()
    {
        $listeners = new Listeners(
            new AutowiringCallableFactory(new Container())
        );
        
        $listener = $listeners->add('Tobento\Service\Event\Test\handleFoo');
        
        $iterator = $listeners->getListenersForEvent(new FooEvent());
        
        $listeners = iterator_to_array($iterator, false);
        
        $this->assertTrue(is_callable($listeners[0]));
    }
    
    public function testAddMethodWithClosure()
    {
        $listeners = new Listeners(
            new AutowiringCallableFactory(new Container())
        );
        
        $listener = $listeners->add(function(FooEvent $event) {
            //
        });
        
        $iterator = $listeners->getListenersForEvent(new FooEvent());
        
        $listeners = iterator_to_array($iterator, false);
        
        $this->assertTrue(is_callable($listeners[0]));
    }
    
    public function testAddMethodWithInvokable()
    {
        $listeners = new Listeners(
            new AutowiringCallableFactory(new Container())
        );
        
        $listener = $listeners->add(new InvokableFooListener());
        
        $iterator = $listeners->getListenersForEvent(new FooEvent());
        
        $listeners = iterator_to_array($iterator, false);
        
        $this->assertTrue(is_callable($listeners[0]));
    }
    
    public function testAddMethodWithInvokableString()
    {
        $listeners = new Listeners(
            new AutowiringCallableFactory(new Container())
        );
        
        $listener = $listeners->add(InvokableFooListener::class);
        
        $iterator = $listeners->getListenersForEvent(new FooEvent());
        
        $listeners = iterator_to_array($iterator, false);
        
        $this->assertTrue(is_callable($listeners[0]));
    }    
    
    public function testAddMethodWithMultipleEvents()
    {
        $listeners = new Listeners(
            new AutowiringCallableFactory(new Container())
        );
        
        $listener = $listeners->add(new FooBarListener());
        
        $iterator = $listeners->getListenersForEvent(new FooEvent());
        
        $this->assertCount(2, $iterator);
        
        $iterator = $listeners->getListenersForEvent(new BarEvent());
        
        $this->assertCount(2, $iterator);
    }
    
    public function testAddMethodWithMultipleEventsString()
    {
        $listeners = new Listeners(
            new AutowiringCallableFactory(new Container())
        );
        
        $listener = $listeners->add(FooBarListener::class);
        
        $iterator = $listeners->getListenersForEvent(new FooEvent());
        
        $this->assertCount(2, $iterator);
        
        $iterator = $listeners->getListenersForEvent(new BarEvent());
        
        $this->assertCount(2, $iterator);
    }     
}