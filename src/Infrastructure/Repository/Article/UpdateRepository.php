<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Repository\Article;

use Abeliani\Blog\Domain\Model\Article;
use Abeliani\Blog\Domain\Repository\Article\UpdateRepositoryInterface;

readonly class UpdateRepository implements UpdateRepositoryInterface
{
    public function __construct(private \PDO $pdo)
    {
    }

    /**
     * @throws \Exception
     */
    public function update(Article $a): void
    {
        try {
            $this->pdo->beginTransaction();

            $sql = 'UPDATE articles SET category_id = ?, status = ?, published_at = ?, updated_at = ?, edited_by = ? WHERE id = ?';
            $this->pdo->prepare($sql)->execute([
                $a->getCategoryId(),
                $a->getStatus()->value,
                $a->getPublishedAt()->format('Y-m-d H:i:s'),
                $a->getUpdatedAt()->format('Y-m-d H:i:s'),
                $a->getEditedBy(),
                $a->getId(),
            ]);

            $sql = <<<SQL
            UPDATE article_translations 
                SET title = ?, slug = ?, content = ?, seo_meta = ?, seo_og = ?, media_image = ?, media_image_alt = ?,
                media_video = ?, status = ?
            WHERE id = ?
            SQL;

            $this->pdo->prepare($sql)->execute([
                $a->getTitle(),
                $a->getSlug(),
                $a->getContent(),
                $a->getSeoMeta(),
                $a->getSeoOg(),
                $a->getImages(),
                $a->getImageAlt(),
                $a->getVideo() ?: null,
                $a->getStatus()->value,
                $a->getTranslateId(),
            ]);

            $this->pdo->prepare('DELETE FROM article_tags WHERE article_translate_id = ?')
                ->execute([$a->getTranslateId()]);

            if (!empty($a->getTags())) {
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
                    $values[] = $a->getTranslateId();
                    $values[] = $id;
                }

                $sql = sprintf(
                    'INSERT INTO article_tags (article_translate_id, tag_id) VALUES %s',
                    implode(',', array_fill(0, count($a->getTags()), '(?, ?)'))
                );

                $this->pdo->prepare($sql)->execute($values);
            }

            $this->pdo->commit();
        } catch (\Exception $e) {
            $this->pdo->rollBack() and throw $e;
        }
    }
}