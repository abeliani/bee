<?php
declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\UI\Web\CPanel;

use Abeliani\Blog\Application\Middleware\JwtAuthenticationMiddleware;
use Abeliani\Blog\Infrastructure\Middleware\WithMiddleware;
use Abeliani\Blog\Infrastructure\Service\Login;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;

#[WithMiddleware(JwtAuthenticationMiddleware::class)]
final readonly class LogoutBackendController implements RequestHandlerInterface
{
    public function __construct(
        private Environment $view,
        private Login $login,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->login->logout();
        return (new Response())
            ->withStatus(302)
            ->withHeader('Location', '/cpanel/login');
    }
}