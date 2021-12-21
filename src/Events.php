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

/**
 * Events
 */
class Events implements EventsInterface
{
    /**
     * @var ListenersInterface
     */
    protected ListenersInterface $listeners;
    
    /**
     * @var DispatcherFactoryInterface
     */
    protected DispatcherFactoryInterface $dispatcherFactory;
    
    /**
     * @var null|EventDispatcherInterface
     */
    protected null|EventDispatcherInterface $dispatcher = null;
    
    /**
     * Create a new Events.
     *
     * @param null|ListenersInterface $listeners
     * @param null|DispatcherFactoryInterface $dispatcherFactory
     */
    public function __construct(
        null|ListenersInterface $listeners = null,
        null|DispatcherFactoryInterface $dispatcherFactory = null,
    ) {
        $this->listeners = $listeners ?: new Listeners();
        $this->dispatcherFactory = $dispatcherFactory ?: new DispatcherFactory();
    }
    
    /**
     * Add a listener.
     *
     * @param mixed $listener
     * @return Listener
     */
    public function listen(mixed $listener): Listener
    {
        return $this->listeners->add($listener);
    }
    
    /**
     * Returns the listeners.
     *
     * @return ListenersInterface
     */
    public function listeners(): ListenersInterface
    {
        return $this->listeners;
    }    
    
    /**
     * Provide all relevant listeners with an event to process.
     *
     * @param object $event
     *   The object to process.
     *
     * @return object
     *   The Event that was passed, now modified by listeners.
     *
     * @psalm-suppress InvalidArgument
     */
    public function dispatch(object $event): object
    {
        if (is_null($this->dispatcher)) {
            $this->dispatcher = $this->dispatcherFactory->createDispatcher($this->listeners());   
        }
        
        return $this->dispatcher->dispatch($event);
    }
}