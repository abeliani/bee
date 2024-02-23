<?php
declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\EventManager;

use Abeliani\Blog\Domain\Service\EventManager\EventInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\Log\LoggerInterface;

final readonly class EventDispatcher implements EventDispatcherInterface
{
    public function __construct(
        private ListenerProviderInterface $provider,
        private LoggerInterface           $logger,
    ) {
    }

    /**
     * @param EventInterface|object $event
     * @throws \Throwable
     */
    public function dispatch(object $event): void
    {
        foreach ($this->provider->getListenersForEvent($event) as $listener) {
            try {
                if ($event->isPropagationStopped()) {
                    break;
                }
                if ($event->isDeferred()) {
                    register_shutdown_function(fn() => $listener($event));
                    continue;
                }
                $listener($event);
            } catch (\Throwable $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                throw $e;
            }
        }
    }
}
