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

namespace Tobento\Service\Event;

/**
 * HasEvents
 */
trait HasEvents
{
    /**
     * @var EventsInterface
     */    
    protected EventsInterface $events;
    
    /**
     * Returns the events.
     *
     * @return EventsInterface
     */
    public function events(): EventsInterface
    {
        return $this->events;
    }
}