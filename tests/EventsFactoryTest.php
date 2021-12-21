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
use Tobento\Service\Event\EventsFactory;
use Tobento\Service\Event\EventsFactoryInterface;
use Tobento\Service\Event\EventsInterface;
use Tobento\Service\Event\Listeners;
use Tobento\Service\Event\DispatcherFactory;

/**
 * EventsFactoryTest
 */
class EventsFactoryTest extends TestCase
{
    public function testThatImplementsEventsFactoryInterface()
    {
        $this->assertInstanceof(
            EventsFactoryInterface::class,
            new EventsFactory()
        );
    }
    
    public function testCreateEvents()
    {
        $factory = new EventsFactory();
        
        $this->assertInstanceof(
            EventsInterface::class,
            $factory->createEvents()
        );
    }
    
    public function testCreateEventsWithParams()
    {
        $factory = new EventsFactory();
        
        $this->assertInstanceof(
            EventsInterface::class,
            $factory->createEvents(
                new Listeners(),
                new DispatcherFactory(),
            )
        );
    }    
}