<?php

declare(strict_types=1);

namespace Abeliani\Blog\Domain\Collection;

final class StreamCollection implements StreamCollectionInterface
{
    private CollectionInterface $stream;

    public function __construct(CollectionInterface $collection)
    {
        $this->stream = $collection->copy();
    }

    public function filter(callable $condition): StreamCollectionInterface
    {
        $collection = $this->stream;
        $this->stream = new Collection($this->stream->getType());

        foreach ($collection as $key => $item) {
            if ($condition($item, $key)) {
                $this->stream->add($item);
            }
        }

        return $this;
    }

    public function sort(callable $comparator): StreamCollectionInterface
    {
        $data = $comparator($this);
        $this->stream = new Collection($this->stream->getType());
        $this->stream->addAll($data);

        return $this;
    }

    public function getCollection(): CollectionInterface
    {
        return $this->stream->copy();
    }
}
