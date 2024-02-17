<?php

namespace Abeliani\Blog\Domain\Repository\User;

use Abeliani\Blog\Domain\Model\User;

interface CreateUserRepositoryInterface
{
    public function create(User $user): bool;
}