<?php
declare(strict_types=1);

namespace Abeliani\Blog\Domain\Trait;

use Abeliani\Blog\Domain\Exception\DomainException;

trait SurrogateId
{
    private  ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @throws DomainException
     */
    public function setId(int $id): void
    {
        if ($this->id !== null) {
            throw new DomainException("ID is already set.");
        }
        $this->id = $id;
    }
}
