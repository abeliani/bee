<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Repository\Article;

use Abeliani\Blog\Domain\Collection\Concrete\ArticleCollection;
use Abeliani\Blog\Domain\Enum\ArticleStatus;
use Abeliani\Blog\Domain\Model\Article;
use Abeliani\Blog\Domain\Repository\Article\ReadRepositoryInterface;
use Abeliani\Blog\Infrastructure\Persistence\Mapper\ArticleMapper;

readonly class ReadRepository implements ReadRepositoryInterface
{
    private const BASE_SQL = <<<SQL
        SELECT a.id, a.category_id, a.created_at, a.published_at, a.updated_at, a.author_id, a.edited_by,
               at.lang, at.title, at.slug, at.preview, at.content, at.seo_meta, at.seo_og, at.media_image,
               at.media_image_alt, at.media_image, at.media_video, at.status, at.view_count, at.id as translate_id,
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
    public function find(int $id): ?Article
    {
        $sql = sprintf('%s WHERE a.id = ? AND a.status = ? GROUP BY a.id LIMIT 1', self::BASE_SQL);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id, ArticleStatus::Published->value]);

        return ($row = $stmt->fetch()) ? $this->mapper->map($row) : null;
    }

    public function findByAuthor(int $id, int $creatorId): ?Article
    {
        $stmt = $this->pdo->prepare(sprintf('%s WHERE a.id = ? AND a.author_id = ? GROUP BY a.id LIMIT 1', self::BASE_SQL));
        $stmt->execute([$id, $creatorId]);

        return ($row = $stmt->fetch()) ? $this->mapper->map($row) : null;
    }

    /**
     * @throws \JsonException
     */
    public function findAll(?ArticleStatus $status = null): ArticleCollection
    {
        $sql = sprintf(
            '%s %s GROUP BY a.id ORDER BY a.published_at DESC',
            self::BASE_SQL,
            ($status !== null ? "WHERE a.status={$status->value}" : '')
        );
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $collection = new ArticleCollection;

        while ($category = $stmt->fetch()) {
            $collection->add($this->mapper->map($category));
        }

        return $collection;
    }

    /**
     * @throws \JsonException
     */
    public function findLast(int $count = 5): ArticleCollection
    {
        $sql = sprintf("%s WHERE a.status = ? GROUP BY a.id ORDER BY a.published_at DESC LIMIT %d", self::BASE_SQL, $count);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([ArticleStatus::Published->value]);
        $collection = new ArticleCollection;

        while ($category = $stmt->fetch()) {
            $collection->add($this->mapper->map($category));
        }

        return $collection;
    }
}
