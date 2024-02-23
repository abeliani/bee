<?php declare(strict_types=1);


namespace Abeliani\Blog\Infrastructure\UI\Web\CPanel;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;

class PostEditController implements RequestHandlerInterface
{
    public function __construct(private readonly Environment $view)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write($this->view->render('post_create.twig', [
            'meta_lang' => 'en',
            'meta_desc' => 'meta description',
            'meta_title' => 'Post Hello, world!',
            'meta_author' => 'Me',
        ]));
        return $response;
    }
}
