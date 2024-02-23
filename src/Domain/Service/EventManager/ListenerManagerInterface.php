<?php

namespace Abeliani\Blog\Domain\Service\EventManager;

interface ListenerManagerInterface
{
    public function getListeners(): array;

    /**
     * @param EventInterface|object $event
     * @return iterable
     */
    public function getListener(object $event): iterable;
    public function addListener(string $eventName, callable $listener): void;
}