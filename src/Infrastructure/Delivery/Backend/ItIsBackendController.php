<?php declare(strict_types=1);


namespace Abeliani\Blog\Infrastructure\Delivery\Backend;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;

class ItIsBackendController implements RequestHandlerInterface
{
    public function __construct(private readonly Environment $view)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write($this->view->render('index.twig', [
            'meta_lang' => 'en',
            'meta_desc' => 'meta description',
            'meta_title' => 'BACKEND Hello, world!',
            'meta_author' => 'Me',
        ]));
        return $response;
    }
}
