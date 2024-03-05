<?php

declare(strict_types=1);

namespace Abeliani\Blog\Domain\Collection;

class Collection implements CollectionInterface
{
    private array $items = [];
    private int $position = 0;

    public function __construct(private readonly string $type)
    {
    }

    public function addAll(mixed $items): void
    {
        foreach ($items as $item) {
            $this->add($item);
        }
    }

    public function add(mixed $item): void
    {
        $this->offsetSet(null, $item);
    }

    public function stream(): StreamCollectionInterface
    {
        return new StreamCollection($this);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function copy(bool $deep = true): Collection {
        $cloned = clone $this;

        if ($deep) {
            $cloned->items = array_map(fn ($item) => is_object($item) ? clone $item : $item, $this->items);
        }

        return $cloned;
    }

    public function offsetSet($offset, $value): void
    {
        if (get_class($value) !== $this->type) {
            throw new \InvalidArgumentException("Value must be of type {$this->type}");
        }

        if ($offset === null) {
            $this->items[] = $value;
            return;
        }

        $this->items[$offset] = $value;
    }

    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return $this->items[$offset] ?? null;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function current(): mixed
    {
        return $this->items[$this->position] ?? null;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function valid(): bool
    {
        return isset($this->items[$this->position]);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function jsonSerialize(): array
    {
        return $this->items;
    }

    public function __toString(): string
    {
        return json_encode($this->jsonSerialize(), JSON_UNESCAPED_SLASHES);
    }
}
