<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Service;

use Abeliani\Blog\Domain\Repository\User\ReadUserRepositoryInterface;
use Abeliani\Blog\Domain\Service\PasswordHasher\PasswordHasherInterface;

final readonly class Login
{
    public function __construct(
        private JWTAuthentication $auth,
        private PasswordHasherInterface $hasher,
        private ReadUserRepositoryInterface $repository
    ) {
    }

    public function login(string $email, string $password): bool
    {
        if (!$user = $this->repository->findByEmail($email)) {
            return false;
        }

        if ($this->hasher->verify($password, $user->getPassword())) {
            $this->auth->generateToken($user)->setToCookie();
            return true;
        }

        return false;
    }

    public function logout(): bool
    {
        return $this->auth->deleteToken();
    }
}
