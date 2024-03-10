<?php
declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Builder;

use Abeliani\Blog\Application\Service\Image\TypeEnum;
use ArrayIterator;
use IteratorAggregate;
use Traversable;

/**
 * @property-read array<string, BuilderActionInterface[]> $builder
 */
class ImageQueryBuilder implements IteratorAggregate
{
    private string $id;
    protected array $builder = [];

    public function __construct(private readonly string $label)
    {
        $this->id = sprintf('image_builder_%d', spl_object_id($this));
    }

    public function append(BuilderActionInterface $action): self
    {
        $this->builder[$action->getType()][] = $action;

        return $this;
    }

    public function lazy(callable $action, ?string $callableType = null): self
    {
        $this->builder[$callableType][] = $action;

        return $this;
    }

    public function branch(ImageQueryBuilder $builder): self
    {
        $this->builder[TypeEnum::branches->name][] = $builder;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return array<string, BuilderActionInterface[]>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->builder);
    }
}
