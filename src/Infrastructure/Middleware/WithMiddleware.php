<?php
declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Middleware;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final readonly class WithMiddleware
{
    public function __construct(private string $middleware)
    {
    }
}
