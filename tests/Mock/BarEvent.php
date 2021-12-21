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
 * BarEvent
 */
class BarEvent
{
    public function __construct(
        private array $messages = [],
    ) {}
    
    public function addMessage(string $message): void
    {
        $this->messages[] = $message;
    }
    
    public function messages(): array
    {
        return $this->messages;
    }    
}