<?php

namespace Abeliani\Blog\Domain\Repository\Subscription;

use Abeliani\Blog\Domain\Model\Subscription;

interface CreateRepositoryInterface
{
    public function save(Subscription $s): void;
    public function activate(Subscription $s): ?Subscription;
}