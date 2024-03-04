<?php

declare(strict_types=1);

namespace Abeliani\Blog\Domain\Exception;

class InvalidEntityException extends \DomainException
{
    protected $code = 400;
}