<?php

namespace Abeliani\Blog\Application\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Random\RandomException;

class CsrfCheckMiddleware implements MiddlewareInterface
{
    public const FIELD_NAME = 'csrf_token';

    /**
     * @throws RandomException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        session_start();

        if ($request->getMethod() === 'GET' && !isset($_SESSION[self::FIELD_NAME])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        if (in_array($request->getMethod(), ['POST', 'PUT', 'DELETE'])) {
            $userToken = $request->getParsedBody()['_csrf'] ?? '';
            $sessionToken = $_SESSION[self::FIELD_NAME] ?? '';

            if (!$userToken || !$sessionToken || !hash_equals($sessionToken, $userToken)) {
                throw new \RuntimeException('Wrong request data');
            }
        }

        $response = $handler->handle($request);

        if ($request->getMethod() === 'GET') {
            $response = $response->withAddedHeader('X-CSRF-Token', $_SESSION[self::FIELD_NAME]);
        }

        return $response;
    }
}