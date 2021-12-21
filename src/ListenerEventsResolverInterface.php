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
 * ListenerEventsResolverInterface
 */
interface ListenerEventsResolverInterface
{
    /**
     * Returns the events with the listener.
     *
     * @param mixed $listener
     * @return array<string, array<mixed>>
     */
    public function resolve(mixed $listener): array;
}