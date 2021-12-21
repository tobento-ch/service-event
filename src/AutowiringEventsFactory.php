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

use Psr\Container\ContainerInterface;

/**
 * AutowiringEventsFactory
 */
class AutowiringEventsFactory implements EventsFactoryInterface
{
    /**
     * Create a new AutowiringEventsFactory.
     *
     * @param ContainerInterface $container
     * @param bool $withAutowiringDispatching
     */
    public function __construct(
        protected ContainerInterface $container,
        protected bool $withAutowiringDispatching = true,
    ) {}
    
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
        
        if (is_null($listeners)) {
           $listeners = new Listeners(
                callableFactory: new AutowiringCallableFactory($this->container)
            ); 
        }
        
        if (is_null($dispatcherFactory) && $this->withAutowiringDispatching) {
            $dispatcherFactory = new DispatcherFactory($this->container);
        }
        
        return new Events($listeners, $dispatcherFactory);
    }
}