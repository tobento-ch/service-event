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
use Tobento\Service\Event\AutowiringEventsFactory;
use Tobento\Service\Event\EventsFactoryInterface;
use Tobento\Service\Event\EventsInterface;
use Tobento\Service\Event\Listeners;
use Tobento\Service\Event\DispatcherFactory;
use Tobento\Service\Container\Container;

/**
 * AutowiringEventsFactoryTest
 */
class AutowiringEventsFactoryTest extends TestCase
{
    public function testThatImplementsEventsFactoryInterface()
    {
        $this->assertInstanceof(
            EventsFactoryInterface::class,
            new AutowiringEventsFactory(new Container())
        );
    }
    
    public function testCreateEvents()
    {
        $factory = new AutowiringEventsFactory(new Container());
        
        $this->assertInstanceof(
            EventsInterface::class,
            $factory->createEvents()
        );
    }
    
    public function testCreateEventsWithParams()
    {
        $factory = new AutowiringEventsFactory(new Container());
        
        $this->assertInstanceof(
            EventsInterface::class,
            $factory->createEvents(
                new Listeners(),
                new DispatcherFactory(),
            )
        );
    }    
}