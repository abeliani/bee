<?php
declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\EventManager;

use Abeliani\Blog\Domain\Service\EventManager\ListenerManagerInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

final readonly class ListenerProvider implements ListenerProviderInterface
{
    public function __construct(private ListenerManagerInterface $manager)
    {
    }

    public function getListenersForEvent(object $event): iterable
    {
        return $this->manager->getListener($event);
    }
}