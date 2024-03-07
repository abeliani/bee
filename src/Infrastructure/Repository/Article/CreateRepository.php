<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Repository\Article;

use Abeliani\Blog\Domain\Exception\DomainException;
use Abeliani\Blog\Domain\Model\Article;
use Abeliani\Blog\Domain\Repository\Article\CreateRepositoryInterface;

readonly class CreateRepository implements CreateRepositoryInterface
{
    public function __construct(private \PDO $pdo)
    {
    }

    /**
     * @throws DomainException
     * @throws \Throwable
     */
    public function create(Article $a): void
    {
        try {
            $this->pdo->beginTransaction();

            $this->pdo->prepare('INSERT INTO articles (status, published_at, author_id) VALUES (?, ?, ?)')
                ->execute([
                    $a->getStatus()->value,
                    $a->getPublishedAt()->format('Y-m-d H:i:s'),
                    $a->getCreatedBy()
                ]);

            $a->setId((int) $this->pdo->lastInsertId());

            $sql = <<<SQL
            INSERT INTO article_translations 
            (article_id, lang, title, slug, content, seo_meta, seo_og, media_image, media_image_alt, media_video, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            SQL;

            $this->pdo->prepare($sql)
                ->execute([
                    $a->getId(),
                    $a->getLanguage()->value,
                    $a->getTitle(),
                    $a->getSlug(),
                    $a->getContent(),
                    $a->getSeoMeta(),
                    $a->getSeoOg(),
                    $a->getImages(),
                    $a->getImageAlt(),
                    $a->getVideo(),
                    $a->getStatus()->value,
                ]);

            if (!empty($a->getTags())) {
                $translationId = (int) $this->pdo->lastInsertId();
                $placeholders = array_fill(0, count($a->getTags()), '?');

                $sql = sprintf('INSERT IGNORE INTO tags (name) VALUES %s', sprintf(
                    '(%s)', implode('), (', $placeholders)
                ));
                $this->pdo->prepare($sql)->execute($a->getTags());

                $sql = sprintf('SELECT id FROM tags WHERE name IN (%s)', implode(',', $placeholders));
                $stm = $this->pdo->prepare($sql);
                $stm->execute($a->getTags());

                $values = [];
                foreach ($stm->fetchAll(\PDO::FETCH_COLUMN, 0) as $id) {
                    $values[] = $translationId;
                    $values[] = $id;
                }

                $sql = sprintf(
                    'INSERT INTO article_tags (article_translate_id, tag_id) VALUES %s',
                    implode(',', array_fill(0, count($a->getTags()), '(?, ?)'))
                );

                $this->pdo->prepare($sql)->execute($values);
            }

            $this->pdo->commit();
        } catch (\Throwable $e) {
            // fixme throw wrapped error
            $this->pdo->rollBack() and throw $e;
        }
    }
}