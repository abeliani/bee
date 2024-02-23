<?php

namespace Abeliani\Blog\Domain\Repository\User;

use Abeliani\Blog\Domain\Model\User;

interface ReadUserRepositoryInterface
{
    public function find(int $id): ?User;
    public function findByEmail(string $email): ?User;
}