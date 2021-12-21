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
 * EventsFactory
 */
class EventsFactory implements EventsFactoryInterface
{
    /**
     * Create a new Events.
     *
     * @param null|ListenersInterface $listeners
     * @param null|DispatcherFactoryInterface $dispatcherFactory
     * @return EventsInterface
     */
    public function createEvents(
        null|ListenersInterface $listeners = null,
        null|DispatcherFactoryInterface $dispatcherFactory = null
    ): EventsInterface {
        return new Events($listeners, $dispatcherFactory);
    }
}