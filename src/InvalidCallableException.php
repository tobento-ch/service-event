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

use InvalidArgumentException;
use Throwable;

/**
 * InvalidCallableException
 */
class InvalidCallableException extends InvalidArgumentException
{
    /**
     * Create a new InvalidCallableException.
     *
     * @param mixed $callable
     * @param string $message The message
     * @param int $code
     * @param null|Throwable $previous
     */
    public function __construct(
        protected mixed $callable,
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null
    ) {
        if ($message === '') {
            
            $callable = $this->convertCallableToString($callable);
            
            $message = 'Callable ['.$callable.'] is invalid';    
        }
        
        parent::__construct($message, $code, $previous);
    }
    
    /**
     * Returns the callable.
     *
     * @return mixed
     */
    public function callable(): mixed
    {
        return $this->callable;
    }

    /**
     * Convert callable to string.
     *
     * @param mixed $callable
     * @return string
     */
    protected function convertCallableToString(mixed $callable): string
    {
        if (is_string($callable)) {
            return $callable;
        }
        
        if (is_object($callable)) {
            return $callable::class;
        }
        
        if (is_array($callable) && isset($callable[0])) {
            return $this->convertCallableToString($callable[0]);
        }        
        
        return '';
    }
}