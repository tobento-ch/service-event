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
use Tobento\Service\Event\AutowiringCallableFactory;
use Tobento\Service\Event\CallableFactoryInterface;
use Tobento\Service\Event\InvalidCallableException;
use Tobento\Service\Container\Container;
use Tobento\Service\Event\Test\Mock\{
    FooListener,
    StaticFooListener,
    ListenerWithParameters,
    ListenerWithBuildInParameter,
};

/**
 * AutowiringCallableFactoryTest
 */
class AutowiringCallableFactoryTest extends TestCase
{
    private function createFactory(): AutowiringCallableFactory
    {
        return new AutowiringCallableFactory(new Container());
    }

    public function testThatImplementsCallableFactoryInterface()
    {
        $factory = $this->createFactory();
        
        $this->assertInstanceof(
            CallableFactoryInterface::class,
            $factory
        );
    }

    public function testCreateFromClosure()
    {
        $factory = $this->createFactory();
        
        $this->assertTrue(is_callable(
            $factory->createCallable(function(FooEvent $event) {
                
            })
        ));
    }
    
    public function testCreateFromArrayWithObject()
    {
        $factory = $this->createFactory();
        
        $this->assertTrue(is_callable(
            $factory->createCallable([new FooListener(), 'foo'])
        ));
    }
    
    public function testCreateFromArrayWithObjectString()
    {
        $factory = $this->createFactory();
        
        $this->assertTrue(is_callable(
            $factory->createCallable([FooListener::class, 'foo'])
        ));
    }
    
    public function testCreateFromArrayWithCallableArray()
    {
        $factory = $this->createFactory();
        
        $this->assertTrue(is_callable(
            $factory->createCallable([StaticFooListener::class, 'foo'])
        ));
    }    
    
    public function testCreateFromArrayWithObjectStringWithParameter()
    {
        $factory = $this->createFactory();
        
        $this->assertTrue(is_callable(
            $factory->createCallable([ListenerWithParameters::class, 'foo'])
        ));
    }    
    
    public function testCreateFromArrayWithObjectStringWithBuildInParameter()
    {
        $factory = $this->createFactory();
        
        $this->assertTrue(is_callable(
            $factory->createCallable([ListenerWithBuildInParameter::class, 'foo', ['number' => 5]])
        ));
    }    
}