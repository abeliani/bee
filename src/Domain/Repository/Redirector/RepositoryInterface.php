<?php

namespace Abeliani\Blog\Domain\Repository\Redirector;

use Abeliani\Blog\Domain\Entity\RedirectUrl;

interface RepositoryInterface
{
    public function create(RedirectUrl $url): bool;
    public function find(string $hash): ?RedirectUrl;
}