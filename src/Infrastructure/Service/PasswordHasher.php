<?php

namespace Abeliani\Blog\Infrastructure\Service;

use Abeliani\Blog\Domain\Service\PasswordHasher\PasswordHasherInterface;

final readonly class PasswordHasher implements PasswordHasherInterface
{
    public function __construct(private int $cost = 10)
    {
    }

    public function hash(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT, ['cost' => $this->cost]);
    }

    public function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public function needsRehash(string $hash): bool
    {
        return password_needs_rehash($hash, PASSWORD_DEFAULT, ['cost' => $this->cost]);
    }

    public function isHashed(string $password): bool
    {
        return password_get_info($password)['algo'] ?? false;
    }
}
