<?php

namespace Abeliani\Blog\Application\Middleware;

use FastRoute\Dispatcher;
use GuzzleHttp\Psr7\Utils;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;

final readonly class RouteDispatcherMiddleware implements MiddlewareInterface
{
    public function __construct(private Environment $view)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $tmpl = $request->getAttribute('dispatcher') === Dispatcher::NOT_FOUND
            ? 'error/404.twig' : 'error/not_allowed.twig';

        return (new Response())->withBody(Utils::streamFor($this->view->render($tmpl)));
    }
}