<?php
declare(strict_types=1);

namespace Abeliani\Blog\Application\Middleware;

use Abeliani\Blog\Application\Enum\AuthRequestAttrs;
use Abeliani\Blog\Application\Event\LogDebugEvent;
use Abeliani\Blog\Application\Event\RequestEvent;
use Abeliani\Blog\Domain\Repository\User\ReadUserRepositoryInterface;
use Abeliani\Blog\Infrastructure\Service\JWTAuthentication;
use GuzzleHttp\Psr7\Response;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final readonly class JwtAuthenticationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private JWTAuthentication $auth,
        private ReadUserRepositoryInterface $repository,
        private EventDispatcherInterface $dispatcher,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($token = $this->auth->getFromCookie()) {
            $user = $this->repository->find($token->claims()->get(AuthRequestAttrs::UserId->value));
        }

        if (empty($user)) {
            $this->dispatcher->dispatch(new RequestEvent('User not found.', $request, $handler));
            return (new Response())
                ->withHeader('Location', sprintf('/cpanel/login?redirect=%s', $request->getUri()))
                ->withStatus(302);
        }

        if (($token->claims()->get('exp')->getTimestamp() - time()) < 21600) {
            $this->dispatcher->dispatch(new RequestEvent('User token prolonged.', $request, $handler));
            $this->auth->generateTokenWIthExpiring($user, '+12 hour')->setToCookie();
        }

        $request = $request->withAttribute(AuthRequestAttrs::CurrentUser->value, $user);
        return $handler->handle($request);
    }
}
