<?php
declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\UI\Web\CPanel;

use Abeliani\Blog\Application\Event\RequestEvent;
use Abeliani\Blog\Infrastructure\Service\Login;
use GuzzleHttp\Psr7\Response;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;

final readonly class LoginBackendController implements RequestHandlerInterface
{
    public function __construct(
        private Environment              $view,
        private Login                    $auth,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $response = new Response();

        if ($request->getMethod() === 'GET') {
            $view = $this->view->render('cpanel/login.twig', [
                'seo_title' => 'Login',
                'login_url' => '/cpanel/login'
            ]);

            $response->getBody()->write($view);
            return $response;
        }

        $data = $request->getParsedBody();
        if ($this->auth->login($data['email'], $data['password'])) {
            $this->eventDispatcher->dispatch(new RequestEvent('User logged in', $request, $this));

            parse_str($request->getHeaderLine('Referer') ?? '', $parsed_url);
            $response = $response
                ->withStatus(302)
                ->withHeader('Location', array_shift($parsed_url) ?? '/cpanel');
        }

        return $response;
    }
}