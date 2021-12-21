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
use Tobento\Service\Event\ListenerEventsResolver;
use Tobento\Service\Event\ListenerEventsResolverInterface;
use Tobento\Service\Event\Test\Mock\FooListener;
use Tobento\Service\Event\Test\Mock\FooBarListener;
use Tobento\Service\Event\Test\Mock\FooEvent;
use Tobento\Service\Event\Test\Mock\BarEvent;
use Tobento\Service\Event\Test\Mock\ListenerWithBuildInParameter;
use Tobento\Service\Event\Test\Mock\StaticFooListener;
use Tobento\Service\Event\Test\Mock\InvokableFooListener;

/**
 * ListenerEventsResolverTest
 */
class ListenerEventsResolverTest extends TestCase
{
    public function testThatImplementsListenerEventsResolverInterface()
    {
        $this->assertInstanceof(
            ListenerEventsResolverInterface::class,
            new ListenerEventsResolver()
        );
    }
    
    public function testWithObject()
    {
        $resolver = new ListenerEventsResolver();
        
        $listener = new FooListener();
        
        $resolved = $resolver->resolve($listener);
        
        $this->assertSame(
            [
                FooEvent::class => [
                    [$listener, 'foo', []],
                ],
            ],
            $resolved
        );
    }
    
    public function testWithObjectInvokable()
    {
        $resolver = new ListenerEventsResolver();
        
        $listener = new InvokableFooListener();
        
        $resolved = $resolver->resolve($listener);
        
        $this->assertSame(
            [
                FooEvent::class => [
                    [$listener, '__invoke', []],
                ],
            ],
            $resolved
        );
    }    
    
    public function testWithObjectString()
    {
        $resolver = new ListenerEventsResolver();
        
        $listener = FooListener::class;
        
        $resolved = $resolver->resolve($listener);
        
        $this->assertSame(
            [
                FooEvent::class => [
                    [$listener, 'foo', []],
                ],
            ],
            $resolved
        );
    }
    
    public function testWithObjectStringBuildInParams()
    {
        $resolver = new ListenerEventsResolver();
        
        $listener = [ListenerWithBuildInParameter::class, ['number' => 5]];
        
        $resolved = $resolver->resolve($listener);
        
        $this->assertSame(
            [
                FooEvent::class => [
                    [ListenerWithBuildInParameter::class, 'foo', ['number' => 5]],
                ],
            ],
            $resolved
        );
    }
    
    public function testWithClosure()
    {
        $resolver = new ListenerEventsResolver();
        
        $listener = function(FooEvent $event) {};
        
        $resolved = $resolver->resolve($listener);
        
        $this->assertSame(
            [
                FooEvent::class => [
                    $listener,
                ],
            ],
            $resolved
        );
    }
    
    public function testWithStaticObject()
    {
        $resolver = new ListenerEventsResolver();
        
        $listener = StaticFooListener::class;
        
        $resolved = $resolver->resolve($listener);
        
        $this->assertSame(
            [
                FooEvent::class => [
                    [$listener, 'foo', []],
                ],
            ],
            $resolved
        );
    }
    
    public function testWithMultipleEvents()
    {
        $resolver = new ListenerEventsResolver();
        
        $listener = FooBarListener::class;
        
        $resolved = $resolver->resolve($listener);
        
        $this->assertSame(
            [
                FooEvent::class => [
                    [$listener, 'foo', []],
                    [$listener, 'foobar', []],
                ],
                BarEvent::class => [
                    [$listener, 'bar', []],
                    [$listener, 'foobar', []],
                ],                
            ],
            $resolved
        );
    }    
}