<?php
declare(strict_types=1);

use Abeliani\Blog\Application\Event\RequestEvent;
use Abeliani\Blog\Application\Listener\LogListener;
use Abeliani\Blog\Application\Service\EventManager\EventDispatcher;
use Abeliani\Blog\Application\Service\EventManager\ListenerManager;
use Abeliani\Blog\Application\Service\EventManager\ListenerProvider;
use Abeliani\Blog\Domain\Service\EventManager\ListenerManagerInterface;
use DI\Container;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\Log\LoggerInterface;

return [
    ListenerProviderInterface::class => function(Container $c): ListenerProviderInterface {
        return new ListenerProvider($c->get(ListenerManagerInterface::class));
    },
    EventDispatcherInterface::class => function(Container $c): EventDispatcherInterface {
        return new EventDispatcher($c->get(ListenerProviderInterface::class), $c->get(LoggerInterface::class));
    },
    ListenerManagerInterface::class => function(Container $c): ListenerManagerInterface {
        $listener = new ListenerManager();
        $listener->addListener(RequestEvent::NAME, new LogListener($c->get(LoggerInterface::class)));
        return $listener;
    },
];