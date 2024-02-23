<?php

namespace Abeliani\Blog\Application\Event;

use Abeliani\Blog\Domain\Service\EventManager\EventInterface;

abstract class LogEvent implements EventInterface
{
    public function __construct(
        private string $level,
        private string $message,
        private array  $context = [],
    ) {
    }

    public function getName(): string
    {
        return static::NAME;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function getLevel(): string
    {
        return $this->level;
    }

    public function isPropagationStopped(): bool
    {
        return false;
    }
}