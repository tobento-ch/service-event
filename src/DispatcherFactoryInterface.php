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
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * DispatcherFactoryInterface
 */
interface DispatcherFactoryInterface
{
    /**
     * Create EventDispatcher.
     *
     * @param ListenerProviderInterface $listenerProvider
     * @return EventDispatcherInterface
     */
    public function createDispatcher(ListenerProviderInterface $listenerProvider): EventDispatcherInterface;
}