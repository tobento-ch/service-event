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

use Psr\Container\ContainerInterface;
use Tobento\Service\Autowire\Autowire;
use Tobento\Service\Autowire\AutowireException;

/**
 * AutowiringCallableFactory
 */
class AutowiringCallableFactory implements CallableFactoryInterface
{
    /**
     * @var Autowire
     */    
    protected Autowire $autowire;
    
    /**
     * Create a new MiddlewareDispatcher.
     *
     * @param ContainerInterface $container
     */
    public function __construct(
        ContainerInterface $container
    ) {
        $this->autowire = new Autowire($container);
    }
    
    /**
     * Create callable.
     *
     * @param mixed $callable
     * @return callable
     *
     * @throws InvalidCallableException
     */
    public function createCallable(mixed $callable): callable
    {
        if (is_callable($callable)) {
            return $callable;
        }
        
        if (
            is_array($callable)
            && isset($callable[0])
            && is_string($callable[0])
        ) {
            
            $parameters = [];
            
            if (isset($callable[2]) && is_array($callable[2])) {
                $parameters = $callable[2];
            }
            
            try {
                $object = $this->autowire->resolve($callable[0], $parameters);
            } catch(AutowireException $e) {
                throw new InvalidCallableException($callable);
            }
            
            $callable = [$object, $callable[1] ?? null];
            
            if (!is_callable($callable)) {
                throw new InvalidCallableException($callable);
            }
            
            return $callable;
        }
        
        if (
            is_array($callable)
            && isset($callable[0])
            && is_object($callable[0])
        ) {            
            $callable = [$callable[0], $callable[1] ?? null];
            
            if (!is_callable($callable)) {
                throw new InvalidCallableException($callable);
            }
            
            return $callable;
        }        
        
        throw new InvalidCallableException($callable);
    }
    
    /**
     * Returns the Autowire.
     *
     * @return Autowire
     */    
    public function autowire(): Autowire
    {
        return $this->autowire;
    }
}