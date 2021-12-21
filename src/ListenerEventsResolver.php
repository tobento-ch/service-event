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

use ReflectionClass;
use ReflectionMethod;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionNamedType;
use ReflectionUnionType;
use Closure;

/**
 * ListenerEventsResolver
 */
class ListenerEventsResolver implements ListenerEventsResolverInterface
{
    /**
     * Create a new ListenerEventsResolver.
     *
     * @param null|string $parameterName
     */
    public function __construct(
        protected null|string $parameterName = 'event'
    ) {}
    
    /**
     * Returns the events with the listener.
     *
     * @param mixed $listener
     * @return array<string, array<mixed>>
     */
    public function resolve(mixed $listener): array
    {
        if ($listener instanceof Closure) {
            return $this->resolveFromCallable($listener);
        }

        if (is_string($listener) && is_callable($listener)) {
            return $this->resolveFromCallable($listener);
        }
        
        if (is_string($listener) || is_object($listener)) {
            return $this->resolveFromClass($listener);
        }

        if (is_callable($listener)) {
            return $this->resolveFromCallable($listener);
        }
        
        if (is_array($listener) && isset($listener[0]) && is_string($listener[0])) {
            
            $parameters = [];
            
            if (isset($listener[1]) && is_array($listener[1])) {
                $parameters = $listener[1];
            }
                
            return $this->resolveFromClass($listener[0], $parameters);
        }        
        
        return [];
    }

    /**
     * Resolve from string
     *
     * @param callable $callable
     * @return array<string, array<mixed>>
     */
    protected function resolveFromCallable(callable $callable): array
    {
        $function = new ReflectionFunction(Closure::fromCallable($callable));
        
        $resolved = [];
        
        foreach($this->resolveEventClasses($function) as $eventClass)
        {
            $resolved[$eventClass][] = $callable;
        }
        
        return $resolved;
    }
    
    /**
     * Resolve from string
     *
     * @param string|object $class
     * @param array $parameters
     * @return array<string, array<mixed>>
     */
    protected function resolveFromClass(string|object $class, array $parameters = []): array
    {        
        $reflectionClass = new ReflectionClass($class);
        
        $reflectionMethods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);
        
        $resolved = [];
        
        foreach($reflectionMethods as $reflectionMethod)
        {
            foreach($this->resolveEventClasses($reflectionMethod) as $eventClass)
            {
                $resolved[$eventClass][] = [
                    is_string($class) ? $reflectionClass->getName() : $class,
                    $reflectionMethod->getName(),
                    $parameters
                ];
            }
        }
        
        return $resolved;
    }
    
    /**
     * Resolves the parameters.
     * 
     * @param string $id
     * @param ReflectionFunctionAbstract $function
     * @param array<int|string, mixed> $parameters
     * @return array<mixed> The resolved parameters.
     */
    protected function resolveEventClasses(ReflectionFunctionAbstract $function): array
    {
        $parameters = $function->getParameters();
        
        if (!isset($parameters[0])) {
            return [];
        }
        
        if (
            $this->parameterName
            && $parameters[0]->getName() !== $this->parameterName)
        {
            return [];
        }
            
        $type = $parameters[0]->getType();
        
        if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
            return [$type->getName()];
        }
        
        if ($type instanceof ReflectionUnionType) {
            
            $names = [];
            
            foreach($type->getTypes() as $namedType)
            {
                if (!$namedType->isBuiltin()) {
                    $names[] = $namedType->getName();    
                }
            }
            
            return $names;
        }
            
        return [];
    }    
}