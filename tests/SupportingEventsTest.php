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
use Tobento\Service\Event\Test\Mock\SupportingEvents;

/**
 * SupportingEventsTest
 */
class SupportingEventsTest extends TestCase
{
    public function testListenMethodReturnsListener()
    {
        $service = new SupportingEvents(new Events());
        
        $this->assertInstanceof(
            EventsInterface::class,
            $service->events()
        );        
    }
}