<?php

namespace Abeliani\Blog\Application\Event;

use Abeliani\Blog\Domain\Service\EventManager\EventInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LogLevel;

class RequestEvent extends LogEvent implements EventInterface
{
    public const NAME = 'request';

    public function __construct(
        string $message,
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
        string $level = LogLevel::DEBUG,
    ) {
        parent::__construct($level, $message, ['context' => [
            'handler' => get_class($handler),
            'uri' => $request->getUri()->__toString(),
        ]]);
    }

    public function isDeferred(): bool
    {
        return true;
    }
}
