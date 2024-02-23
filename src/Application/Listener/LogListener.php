<?php

namespace Abeliani\Blog\Application\Listener;

use Abeliani\Blog\Application\Event\LogEvent;
use Psr\Log\LoggerInterface;

final readonly class LogListener
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(LogEvent $event): void
    {
        call_user_func([$this->logger, $event->getLevel()], $event->getMessage(), $event->getContext());
    }
}
