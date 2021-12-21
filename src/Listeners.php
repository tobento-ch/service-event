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

use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * Listeners
 */
class Listeners implements ListenerProviderInterface, ListenersInterface
{
    /**
     * @var CallableFactoryInterface
     */
    protected CallableFactoryInterface $callableFactory;
    
    /**
     * @var ListenerEventsResolverInterface
     */
    protected ListenerEventsResolverInterface $listenerEventsResolver;
    
    /**
     * @var array<int, ListenerInterface>
     */
    protected array $listeners = [];
    
    /**
     * Create a new Listeners.
     *
     * @param null|CallableFactoryInterface $callableFactory
     * @param null|ListenerEventsResolverInterface $listenerEventsResolver
     */
    public function __construct(
        null|CallableFactoryInterface $callableFactory = null,
        null|ListenerEventsResolverInterface $listenerEventsResolver = null,
    ) {
        $this->callableFactory = $callableFactory ?: new CallableFactory();
        $this->listenerEventsResolver = $listenerEventsResolver ?: new ListenerEventsResolver();
    }

    /**
     * Add a listener.
     *
     * @param ListenerInterface $listener
     * @return static $this
     */
    public function addListener(ListenerInterface $listener): static
    {
        $this->listeners[] = $listener;
        return $this;
    }
    
    /**
     * Add a listener.
     *
     * @param mixed $listener
     * @return Listener
     */
    public function add(mixed $listener): Listener
    {
        return $this->listeners[] = new Listener(
            $listener,
            $this->listenerEventsResolver->resolve($listener)
        );
    }
    
    /**
     * Returns the listeners.
     *
     * @return array<int, ListenerInterface>
     */
    public function all(): array
    {
        return $this->listeners;
    }    

    /**
     * @param object $event
     *   An event for which to return the relevant listeners.
     * @return iterable<callable>
     *   An iterable (array, iterator, or generator) of callables. Each
     *   callable MUST be type-compatible with $event.
     */
    public function getListenersForEvent(object $event): iterable
    {
        $listeners = $this->listeners;
        
        usort(
            $listeners,
            fn (ListenerInterface $a, ListenerInterface $b): int
                => $b->getPriority() <=> $a->getPriority()
        );
        
        foreach($listeners as $listener)
        {
            yield from $listener->getForEvent($event, $this->callableFactory);
        }
    }
}