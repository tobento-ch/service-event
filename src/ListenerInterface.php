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
 * ListenerInterface
 */
interface ListenerInterface
{
    /**
     * Returns the listener.
     *    
     * @return mixed
     */
    public function getListener(): mixed;

    /**
     * Returns the listener events.
     *    
     * @return array<string, array<mixed>>
     */
    public function getListenerEvents(): array;
    
    /**
     * Returns the listeners for the specified event.
     *
     * @param object $event
     * @param CallableFactoryInterface $callableFactory
     * @return iterable<callable>
     *   An iterable (array, iterator, or generator) of callables. Each
     *   callable MUST be type-compatible with $event.
     */
    public function getForEvent(object $event, CallableFactoryInterface $callableFactory): iterable;
    
    /**
     * Returns the priority.
     *    
     * @return int
     */
    public function getPriority(): int;    
}