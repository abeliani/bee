<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Repository\Tag;

use Abeliani\Blog\Domain\Collection\CollectionInterface;
use Abeliani\Blog\Domain\Collection\Concrete\TagCollection;
use Abeliani\Blog\Domain\Model\Tag;
use Abeliani\Blog\Domain\Repository\Tag\ReadRepositoryInterface;

class ReadRepository implements ReadRepositoryInterface
{

    public function __construct(private \PDO $pdo)
    {
    }

    public function findAll(): CollectionInterface
    {
        $stmt = $this->pdo->prepare('SELECT id, name, frequency FROM tags');
        $stmt->execute();
        $collection = new TagCollection;

        while ($tag = $stmt->fetch()) {
            $collection->add(new Tag($tag['id'], $tag['name'], $tag['frequency']));
        }

        return $collection;
    }
}