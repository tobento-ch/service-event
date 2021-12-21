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
use Tobento\Service\Event\DispatcherFactory;
use Tobento\Service\Event\DispatcherFactoryInterface;
use Tobento\Service\Event\Listeners;
use Tobento\Service\Container\Container;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * DispatcherFactoryTest
 */
class DispatcherFactoryTest extends TestCase
{
    public function testThatImplementsDispatcherFactoryInterface()
    {
        $this->assertInstanceof(
            DispatcherFactoryInterface::class,
            new DispatcherFactory()
        );
    }
    
    public function testCreateDispatcher()
    {
        $factory = new DispatcherFactory();
        
        $dispatcher = $factory->createDispatcher(new Listeners());
        
        $this->assertInstanceof(
            EventDispatcherInterface::class,
            $dispatcher
        );
    }
    
    public function testCreateDispatcherWithContainer()
    {
        $factory = new DispatcherFactory(new Container());
        
        $dispatcher = $factory->createDispatcher(new Listeners());
        
        $this->assertInstanceof(
            EventDispatcherInterface::class,
            $dispatcher
        );
    }    
}