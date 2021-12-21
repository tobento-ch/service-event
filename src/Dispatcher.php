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

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;
use Psr\Container\ContainerInterface;
use Tobento\Service\Autowire\Autowire;

/**
 * Dispatcher
 */
class Dispatcher implements EventDispatcherInterface
{
    /**
     * @var null|Autowire
     */    
    protected null|Autowire $autowire = null;
    
    /**
     * Create a new EventDispatcher.
     *
     * @param ListenerProviderInterface $listenerProvider
     * @param null|ContainerInterface $container
     */
    public function __construct(
        protected ListenerProviderInterface $listenerProvider,
        null|ContainerInterface $container = null,
    ) {
        if ($container) {
            $this->autowire = new Autowire($container);
        }
    }
    
    /**
     * Provide all relevant listeners with an event to process.
     *
     * @param object $event
     *   The object to process.
     *
     * @return object
     *   The Event that was passed, now modified by listeners.
     */
    public function dispatch(object $event): object
    {
        $stoppable = $event instanceof StoppableEventInterface;
        
        foreach($this->listenerProvider->getListenersForEvent($event) as $listener)
        {
            if (
                $stoppable
                && $event->isPropagationStopped()
            ) {
                return $event;
            }
            
            if (!is_null($this->autowire)) {
                $this->autowire->call($listener, [$event]);
            } else {
                $listener($event);   
            }
        }

        return $event;
    }
}