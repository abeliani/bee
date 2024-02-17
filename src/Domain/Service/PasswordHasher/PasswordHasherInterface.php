<?php

namespace Abeliani\Blog\Domain\Service\PasswordHasher;

interface PasswordHasherInterface
{
    public function hash(string $password): string;
    public function verify(string $password, string $hash): bool;
    public function needsRehash(string $hash): bool;
    public function isHashed(string $password): bool;
}
