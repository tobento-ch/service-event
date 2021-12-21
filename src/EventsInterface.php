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
 * EventsInterface
 */
interface EventsInterface extends EventDispatcherInterface
{
    /**
     * Add a listener.
     *
     * @param mixed $listener
     * @return Listener
     */
    public function listen(mixed $listener): Listener;
    
    /**
     * Returns the listeners.
     *
     * @return ListenersInterface
     */
    public function listeners(): ListenersInterface; 
}