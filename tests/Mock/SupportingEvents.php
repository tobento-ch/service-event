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

namespace Tobento\Service\Event\Test\Mock;

use Tobento\Service\Event\EventsAware;
use Tobento\Service\Event\HasEvents;
use Tobento\Service\Event\EventsInterface;

/**
 * SupportingEvents
 */
class SupportingEvents implements EventsAware
{
    use HasEvents;
    
    public function __construct(EventsInterface $events)
    {
        $this->events = $events;
    }  
}