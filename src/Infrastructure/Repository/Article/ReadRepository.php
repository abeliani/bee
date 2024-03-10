<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Repository\Article;

use Abeliani\Blog\Domain\Collection\Concrete\ArticleCollection;
use Abeliani\Blog\Domain\Model\Article;
use Abeliani\Blog\Domain\Repository\Article\ReadRepositoryInterface;
use Abeliani\Blog\Infrastructure\Persistence\Mapper\ArticleMapper;

readonly class ReadRepository implements ReadRepositoryInterface
{
    private const BASE_SQL = <<<SQL
        SELECT a.id, a.created_at, a.published_at, a.updated_at, a.author_id, a.edited_by,
               at.lang, at.title, at.slug, at.content, at.seo_meta, at.seo_og, at.media_image,
               at.media_image_alt, at.media_image, at.media_video, at.status, at.view_count,
               GROUP_CONCAT(t.name SEPARATOR ', ') AS tags
        FROM articles a
        INNER JOIN article_translations at ON a.id = at.article_id AND at.lang='ru'
        LEFT JOIN article_tags atag ON at.id = atag.article_translate_id
        LEFT JOIN tags t ON atag.tag_id = t.id
SQL;

    public function __construct(private \PDO $pdo, private ArticleMapper $mapper)
    {
    }

    /**
     * @throws \JsonException
     */
    public function find(int $id, int $creatorId): ?Article
    {
        $stmt = $this->pdo->prepare(sprintf('%s WHERE a.id = ? AND a.author_id = ? GROUP BY a.id LIMIT 1', self::BASE_SQL));
        $stmt->execute([$id, $creatorId]);

        return ($row = $stmt->fetch()) ? $this->mapper->map($row) : null;
    }

    /**
     * @throws \JsonException
     */
    public function findAll(): ArticleCollection
    {
        $stmt = $this->pdo->prepare(self::BASE_SQL . ' GROUP BY a.id ORDER BY a.published_at DESC');
        $stmt->execute();
        $collection = new ArticleCollection;

        while ($category = $stmt->fetch()) {
            $collection->add($this->mapper->map($category));
        }

        return $collection;
    }
}
