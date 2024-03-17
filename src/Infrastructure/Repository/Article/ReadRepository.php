<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Repository\Article;

use Abeliani\Blog\Domain\Collection\CollectionInterface;
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
               at.media_image_alt, at.media_video, at.status, at.view_count, at.id as translate_id,
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
    public function findAll(int $limit, ?ArticleStatus $status = null): ArticleCollection
    {
        $sql = sprintf(
            '%s %s GROUP BY a.id ORDER BY a.id DESC, a.published_at DESC LIMIT ?',
            self::BASE_SQL,
            ($status !== null ? "WHERE a.status={$status->value}" : '')
        );

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$limit]);
        $collection = new ArticleCollection;

        while ($category = $stmt->fetch()) {
            $collection->add($this->mapper->map($category));
        }

        return $collection;
    }

    public function finaByCategory(int $creatorId, int $limit, ?ArticleStatus $status = null): ArticleCollection
    {
        $sql = sprintf(
            '%s %s GROUP BY a.id ORDER BY a.id DESC, a.published_at DESC LIMIT ?',
            self::BASE_SQL,
            ($status !== null ? 'WHERE a.status= ? AND a.category_id = ?' : '')
        );

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$status->value, $creatorId, $limit]);
        $collection = new ArticleCollection;

        while ($category = $stmt->fetch()) {
            $collection->add($this->mapper->map($category));
        }

        return $collection;
    }

    public function findByTagId(int $tagId, ?ArticleStatus $status = ArticleStatus::Published): CollectionInterface
    {
        $sql = <<<SQL
   SELECT a.id, a.category_id, a.created_at, a.published_at, a.updated_at, a.author_id, a.edited_by,
               at.lang, at.title, at.slug, at.preview, at.content, at.seo_meta, at.seo_og, at.media_image,
               at.media_image_alt, at.media_video, at.status, at.view_count, at.id as translate_id,
               GROUP_CONCAT(t.name SEPARATOR ', ') AS tags
        FROM articles a
        INNER JOIN article_translations at ON a.id = at.article_id AND at.lang='ru'
SQL;

        $sql = sprintf(
            '%s %s %s WHERE atag.tag_id = ? AND a.status = ? GROUP BY a.id',
            $sql,
            'INNER JOIN article_tags atag ON at.id = atag.article_translate_id',
            'INNER JOIN tags t ON t.id = atag.tag_id'
        );

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$tagId, $status->value]);
        $collection = new ArticleCollection;

        while ($category = $stmt->fetch()) {
            $collection->add($this->mapper->map($category));
        }

        return $collection;
    }

    public function findByCursor(int $cursor, int $direction, int $limit, ?ArticleStatus $status = null): CollectionInterface
    {
        $sql = sprintf(
            '%s WHERE %s %s GROUP BY a.id, a.published_at, a.status ORDER BY a.id %s, a.published_at %s LIMIT ?',
            self::BASE_SQL,
            $direction ? 'a.id < ?' : 'a.id > ?',
            ($status !== null ? "AND a.status={$status->value}" : ''),
            $direction ? 'DESC' : '',
            $direction ? 'DESC' : '',
        );

        if (!$direction) {
            $sql = sprintf('SELECT * FROM (%s) AS o ORDER BY o.id DESC, o.published_at DESC', $sql);
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$cursor, $limit]);
        $collection = new ArticleCollection;

        while ($category = $stmt->fetch()) {
            $collection->add($this->mapper->map($category));
        }

        return $collection;
    }

    public function findFirstId(): int
    {
        $stmt = $this->pdo->prepare('SELECT id FROM articles ORDER BY id LIMIT 1');
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function findLastId(): int
    {
        $stmt = $this->pdo->prepare('SELECT id FROM articles ORDER BY id DESC LIMIT 1');
        $stmt->execute();

        return (int) $stmt->fetchColumn();
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

    /**
     * @throws \JsonException
     */
    public function search(string $therm, int $limit, ArticleStatus $status = ArticleStatus::Published): ArticleCollection
    {
        $sql = sprintf(
            '%s WHERE a.status = ? AND MATCH(title, preview, content) AGAINST(? IN NATURAL LANGUAGE MODE) GROUP BY a.id, a.published_at, a.status ORDER BY a.id DESC, a.published_at DESC LIMIT ?',
            self::BASE_SQL
        );

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$status->value, $therm, $limit]);
        $collection = new ArticleCollection;

        while ($category = $stmt->fetch()) {
            $collection->add($this->mapper->map($category));
        }

        return $collection;
    }
}
