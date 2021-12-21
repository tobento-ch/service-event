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
 * ListenersInterface
 */
interface ListenersInterface
{
    /**
     * Add a listener.
     *
     * @param ListenerInterface $listener
     * @return static $this
     */
    public function addListener(ListenerInterface $listener): static;
    
    /**
     * Add a listener.
     *
     * @param mixed $listener
     * @return Listener
     */
    public function add(mixed $listener): Listener;
    
    /**
     * Returns the listeners.
     *
     * @return array<int, ListenerInterface>
     */
    public function all(): array;
}