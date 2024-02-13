<?php declare(strict_types=1);


namespace Abeliani\Blog\Infrastructure\Delivery\API;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;

class ItWorksController implements RequestHandlerInterface
{
    public function __construct(private readonly Environment $view)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write($this->view->render('index.html'));
        return $response;
    }
}
