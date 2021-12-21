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
use Tobento\Service\Event\Events;
use Tobento\Service\Event\EventsInterface;
use Tobento\Service\Event\ListenersInterface;
use Tobento\Service\Event\Listeners;
use Tobento\Service\Event\Listener;
use Tobento\Service\Event\DispatcherFactoryInterface;
use Tobento\Service\Event\AutowiringCallableFactory;
use Tobento\Service\Container\Container;
use Tobento\Service\Event\Test\Mock\FooListener;
use Tobento\Service\Event\Test\Mock\FooEvent;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * EventsTest
 */
class EventsTest extends TestCase
{
    public function testThatImplementsEventsInterface()
    {
        $this->assertInstanceof(
            EventsInterface::class,
            new Events()
        );
    }
    
    public function testThatImplementsEventDispatcherInterface()
    {
        $this->assertInstanceof(
            EventDispatcherInterface::class,
            new Events()
        );
    }    
    
    public function testListenMethodReturnsListener()
    {
        $events = new Events();
        
        $listener = $events->listen(function(FooEvent $event) {
            //
        });
        
        $this->assertInstanceof(
            Listener::class,
            $listener
        );
    }
    
    public function testListenersMethod()
    {
        $events = new Events();
        
        $this->assertInstanceof(
            ListenersInterface::class,
            $events->listeners()
        );
    }    
    
    public function testDispatchMethod()
    {
        $events = new Events();
        
        $events->listen(function(FooEvent $event) {
            $event->addMessage('1');
        });
        
        $events->listen(function(FooEvent $event) {
            $event->addMessage('2');
        });        
        
        $this->assertSame(
            ['1', '2'],
            $events->dispatch(new FooEvent())->messages()
        );
    }
    
    public function testWithAutowiringListeners()
    {
        $events = new Events(
            new Listeners(new AutowiringCallableFactory(new Container()))
        );
        
        $events->listen(FooListener::class);    
        
        $this->assertSame(
            [FooListener::class],
            $events->dispatch(new FooEvent())->messages()
        );
    }     
}