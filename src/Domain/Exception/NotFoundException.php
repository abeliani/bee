<?php

declare(strict_types=1);

namespace Abeliani\Blog\Domain\Exception;

class NotFoundException extends DomainException
{
    protected $code = 404;
}