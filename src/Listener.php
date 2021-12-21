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
 * Listener
 */
class Listener implements ListenerInterface
{
    /**
     * @var array<int, string> The events to call. If empty all events will be called.
     */
    protected array $events = [];
    
    /**
     * @var int
     */
    protected int $priority = 1000;
    
    /**
     * Create a new Listener.
     *
     * @param mixed $listener
     * @param array<string, array<mixed>> $listenerEvents
     */
    public function __construct(
        protected mixed $listener,
        protected array $listenerEvents,
    ) {}

    /**
     * Set the event(s).
     *
     * @param string $event
     * @return static $this
     */
    public function event(string ...$event): static
    {
        $this->events = $event;
        return $this;
    }
    
    /**
     * Set the priority.
     *
     * @param int $priority
     * @return static $this
     */
    public function priority(int $priority): static
    {
        $this->priority = $priority;
        return $this;
    }  

    /**
     * Returns the listener.
     *    
     * @return mixed
     */
    public function getListener(): mixed
    {
        return $this->listener;
    }

    /**
     * Returns the listener events.
     *    
     * @return array<string, array<mixed>>
     */
    public function getListenerEvents(): array
    {
        return $this->listenerEvents;
    }
    
    /**
     * Returns the listeners for the specified event.
     *
     * @param object $event
     * @param CallableFactoryInterface $callableFactory
     * @return iterable<callable>
     *   An iterable (array, iterator, or generator) of callables. Each
     *   callable MUST be type-compatible with $event.
     */
    public function getForEvent(object $event, CallableFactoryInterface $callableFactory): iterable
    {
        if (
            !empty($this->events)
            && !in_array($event::class, $this->events)
        ) {
            return [];
        }
        
        $events = $this->listenerEvents[$event::class] ?? [];
        
        foreach($events as $listener)
        {
            yield $callableFactory->createCallable($listener);
        }
    }
    
    /**
     * Returns the priority.
     *    
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }
}