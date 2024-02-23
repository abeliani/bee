<?php

namespace Abeliani\Blog\Domain\Mapper;

interface AggregateMapperInterface
{
    public function map(array $data): object;

    public function mapMany(array $data): array;
}