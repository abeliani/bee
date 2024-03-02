<?php
declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Processor;

readonly class ProcessorContext
{
    public function __construct(private array $data)
    {
    }

    public function get(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }
}
