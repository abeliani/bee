<?php
declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\EventManager;

use Abeliani\Blog\Domain\Service\EventManager\ListenerManagerInterface;

final class ListenerManager implements ListenerManagerInterface
{
    private array $listeners = [];

    public function addListener(string $eventName, callable $listener): void
    {
        $this->listeners[$eventName][] = $listener;
    }

    /**
     * @inheritDoc
     */
    public function getListener(object $event): iterable
    {
        yield from $this->listeners[$event->getName()] ?? [];
    }

    public function getListeners(): array
    {
        return $this->listeners;
    }
}