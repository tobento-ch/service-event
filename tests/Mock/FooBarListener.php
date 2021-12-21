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

namespace Tobento\Service\Event\Test\Mock;

/**
 * FooBarListener
 */
class FooBarListener
{
    public function foo(FooEvent $event): void
    {
        // do something
    }
    
    public function bar(BarEvent $event): void
    {
        // do something
    }
    
    public function foobar(FooEvent|BarEvent $event): void
    {
        // do something
    }
    
    protected function fooProtected(BarEvent $event): void
    {
        // protected
    }    
}