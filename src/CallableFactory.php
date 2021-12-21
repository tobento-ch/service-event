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
 * CallableFactory
 */
class CallableFactory implements CallableFactoryInterface
{    
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
}