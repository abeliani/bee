<?php

namespace Abeliani\Blog\Application\Middleware;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Utils;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;

final readonly class ErrorHandlerMiddleware implements MiddlewareInterface
{
    public function __construct(private Environment $view, private Logger $log)
    {
    }
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $response =  $handler->handle($request);
            if ($response->getStatusCode() == 200 || $response->getStatusCode() == 302) {
                return $response;
            }
            switch ($response->getStatusCode()) {
                case 403:
                    $responseBody = $this->view->render('error/403.twig', ['message' => '403 - access forbidden']);
                    return $response->withBody(Utils::streamFor($responseBody));
            }

            throw new \RuntimeException(sprintf('Unknown error %d', $response->getStatusCode()));
        } catch (\Throwable $e) {
            $isDevelopment = true;
            $response = new Response();
            if ($isDevelopment) {
                $whoops = new \Whoops\Run;
                $whoops->allowQuit(false);
                $whoops->writeToOutput(false);
                $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
                $html = $whoops->handleException($e);

                $this->log->error(sprintf("%s in %s:%d", $e->getMessage(), $e->getFile(), $e->getLine()));

                return $response
                    ->withBody(Utils::streamFor($html))
                    ->withHeader('Content-Type', 'text/html')
                    ->withStatus($e->getCode() ?: 500);
            } else {
                $this->log->error(sprintf("%s in %s:%d", $e->getMessage(), $e->getFile(), $e->getLine()));
                $responseBody = $this->view->render('error/500.twig', ['message' => 'Internal Server Error']);
                $response = new Response();
                $response->getBody()->write($responseBody);
                return $response->withHeader('Content-Type', 'text/html')->withStatus(500);
            }
        }
    }
}
