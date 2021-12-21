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
use Tobento\Service\Event\Dispatcher;
use Tobento\Service\Event\Listeners;
use Psr\EventDispatcher\EventDispatcherInterface;
use Tobento\Service\Event\Test\Mock\FooListener;
use Tobento\Service\Event\Test\Mock\FooEvent;
use Tobento\Service\Event\Test\Mock\StoppableEvent;

/**
 * DispatcherTest
 */
class DispatcherTest extends TestCase
{
    public function testThatImplementsEventDispatcherInterface()
    {
        $this->assertInstanceof(
            EventDispatcherInterface::class,
            new Dispatcher(new Listeners())
        );
    }
    
    public function testCallsListeners()
    {
        $listeners = new Listeners();
        
        $listeners->add(function(FooEvent $event) {
            $event->addMessage('1');
        });
        
        $listeners->add(function(FooEvent $event) {
            $event->addMessage('2');
        });
        
        $listeners->add(function(FooEvent $event) {
            $event->addMessage('3');
        });        
        
        $dispatcher = new Dispatcher($listeners);
            
        $this->assertSame(
            ['1', '2', '3'],
            $dispatcher->dispatch(new FooEvent())->messages()
        );
    }
    
    public function testPropagationStops()
    {
        $listeners = new Listeners();
        
        $listeners->add(function(StoppableEvent $event) {
            $event->addMessage('1');
            $event->stop();
        });
        
        $listeners->add(function(StoppableEvent $event) {
            $event->addMessage('2');
        });
        
        $listeners->add(function(StoppableEvent $event) {
            $event->addMessage('3');
        });        
        
        $dispatcher = new Dispatcher($listeners);
            
        $this->assertSame(
            ['1'],
            $dispatcher->dispatch(new StoppableEvent())->messages()
        );
    }    
}