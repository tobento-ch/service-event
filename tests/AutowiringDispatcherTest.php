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
use Tobento\Service\Event\Test\Mock\FooListener;
use Tobento\Service\Event\Test\Mock\FooEvent;
use Tobento\Service\Event\Test\Mock\Foo;
use Tobento\Service\Container\Container;

/**
 * AutowiringDispatcherTest
 */
class AutowiringDispatcherTest extends TestCase
{    
    public function testCallsListeners()
    {
        $listeners = new Listeners();
        
        $listeners->add(function(FooEvent $event) {
            $event->addMessage('1');
        });
        
        $listeners->add(function(FooEvent $event, Foo $foo) {
            $event->addMessage('2');
        });
        
        $listeners->add(function(FooEvent $event) {
            $event->addMessage('3');
        });        
        
        $dispatcher = new Dispatcher($listeners, new Container());
            
        $this->assertSame(
            ['1', '2', '3'],
            $dispatcher->dispatch(new FooEvent())->messages()
        );
    }
}