<?php

namespace Abeliani\Blog\Domain\Repository\Subscription;

use Abeliani\Blog\Domain\Model\Subscription;

interface ReadRepositoryInterface
{
    public function findByEmail(string $email): ?Subscription;
}