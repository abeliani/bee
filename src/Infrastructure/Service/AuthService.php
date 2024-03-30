<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Service;

use Abeliani\Blog\Application\Enum\AuthRequestAttrs;
use Abeliani\Blog\Domain\Model\User;
use Psr\Http\Message\ServerRequestInterface;

class AuthService
{
    public static function extractUser(ServerRequestInterface $request): User
    {
        /** @var User $user */
        $user = $request->getAttribute(AuthRequestAttrs::CurrentUser->value);
        return $user;
    }
}