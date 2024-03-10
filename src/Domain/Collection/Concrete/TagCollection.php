<?php

declare(strict_types=1);

namespace Abeliani\Blog\Domain\Collection\Concrete;

use Abeliani\Blog\Domain\Collection\Collection;
use Abeliani\Blog\Domain\Model\Tag;

class TagCollection extends Collection
{
    public function __construct()
    {
        parent::__construct(Tag::class);
    }
}
