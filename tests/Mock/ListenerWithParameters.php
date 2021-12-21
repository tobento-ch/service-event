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
 * ListenerWithParameters
 */
class ListenerWithParameters
{
    public function __construct(
        protected Foo $foo,
    ) {}
    
    public function foo(FooEvent $event): void
    {
        // do something
    }
}