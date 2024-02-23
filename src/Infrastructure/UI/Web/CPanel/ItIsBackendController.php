<?php declare(strict_types=1);


namespace Abeliani\Blog\Infrastructure\UI\Web\CPanel;

use Abeliani\Blog\Application\Middleware\JwtAuthenticationMiddleware;
use Abeliani\Blog\Infrastructure\Middleware\WithMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;

#[WithMiddleware(JwtAuthenticationMiddleware::class)]
final readonly class ItIsBackendController implements RequestHandlerInterface
{
    public function __construct(private Environment $view)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write($this->view->render('cpanel/index.twig', [
            'meta_lang' => 'en',
            'meta_desc' => 'meta description',
            'meta_title' => 'BACKEND Hello, world!',
            'meta_author' => 'Me',
            'logout_url' => '/cpanel/logout',
        ]));
        return $response;
    }
}
