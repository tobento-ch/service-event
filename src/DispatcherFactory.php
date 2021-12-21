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
use Psr\Container\ContainerInterface;

/**
 * DispatcherFactory
 */
class DispatcherFactory implements DispatcherFactoryInterface
{
    /**
     * Create a new DispatcherFactory.
     *
     * @param null|ContainerInterface $container
     */
    public function __construct(
        protected null|ContainerInterface $container = null,
    ) {}
    
    /**
     * Create EventDispatcher.
     *
     * @param ListenerProviderInterface $listenerProvider
     * @return EventDispatcherInterface
     */
    public function createDispatcher(ListenerProviderInterface $listenerProvider): EventDispatcherInterface
    {
        return new Dispatcher($listenerProvider, $this->container);
    }
}