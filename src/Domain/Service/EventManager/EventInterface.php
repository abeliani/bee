<?php

namespace Abeliani\Blog\Domain\Service\EventManager;

use Psr\EventDispatcher\StoppableEventInterface;

interface EventInterface extends StoppableEventInterface
{
    public function getName(): string;

    /**
     * Will be pass to registered_shutdown_function
     * @return bool
     */
    public function isDeferred(): bool;
}
