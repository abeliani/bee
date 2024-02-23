<?php

namespace Abeliani\Blog\Domain\Service\EventManager;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

interface LogEventInterface extends LoggerInterface
{
    public function getLevel(): string;
    public function getMessage(): string;
    public function getContext(): array;
}