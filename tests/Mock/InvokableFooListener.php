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
 * InvokableFooListener
 */
class InvokableFooListener
{
    public function __invoke(FooEvent $event): void
    {
        // do something
    }
}