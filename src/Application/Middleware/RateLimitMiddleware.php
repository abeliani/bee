<?php

declare(strict_types=1);

namespace Abeliani\Blog\Application\Middleware;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final readonly class RateLimitMiddleware implements MiddlewareInterface
{
    private const KEY_PREFIX = 'rate_limit';

    public function __construct(private \Memcached $mc, private int $limit = 100, private int $period = 3600)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $key = sprintf('%s_%s', self::KEY_PREFIX, str_replace(
            '.', '_', $request->getServerParams()['SERVER_ADDR']
        ));
        $current = $this->mc->get($key);

        if ($current === false) {
            $this->mc->set($key, 1, $this->period);
        } else {
            if ($current >= $this->limit) {
                return (new Response())
                    ->withStatus(429)
                    ->withBody(Utils::streamFor('Too Many Requests'));
            } else {
                $this->mc->increment($key);
            }
        }

        return $handler->handle($request);
    }
}