<?php

namespace Abeliani\Blog\Infrastructure\Repository\Upload;

use Abeliani\Blog\Domain\Collection\Concrete\ImageCollection;
use Abeliani\Blog\Domain\Collection\Concrete\ImageUploadCollection;
use Abeliani\Blog\Domain\Entity\Image;
use Abeliani\Blog\Domain\Enum\UploadTag;
use Abeliani\Blog\Domain\Model\ImageUpload;
use Abeliani\Blog\Domain\Repository\Upload\ImageRepositoryInterface;

class ImageRepository implements ImageRepositoryInterface
{
    public function __construct(private \PDO $pdo)
    {
    }

    public function findAll(?UploadTag $tag = null): ImageUploadCollection
    {
        $files = new ImageUploadCollection;
        $sql = 'SELECT id, tag, title, files, created_by, created_at FROM uploads';

        if ($tag !== null) {
            $sql .= sprintf(' WHERE tag=\'%s\'', $tag->name);
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        while ($file = $stmt->fetch()) {
            $images = new ImageCollection;
            foreach (json_decode($file['files'], true) as $img) {
                $images->add(new Image($img['type'], $img['url']));
            }
            $files->add(new ImageUpload(
                (int) $file['id'],
                $file['title'],
                (int) $file['created_by'],
                UploadTag::article,
                $images
            ));
        }

        return $files;
    }

    public function find(int $id): ?ImageUpload
    {
        $sql = 'SELECT id, tag, title, files, created_by, created_at FROM uploads WHERE id = ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);

        if (!$file = $stmt->fetch()) {
            return null;
        }

        $images = new ImageCollection;
        foreach (json_decode($file['files'], true) as $img) {
            $images->add(new Image($img['type'], $img['url']));
        }

        return new ImageUpload(
            (int) $file['id'],
            $file['title'],
            (int) $file['created_by'],
            UploadTag::article,
            $images
        );
    }

    public function save(ImageUpload $iu): bool
    {
        return $this->pdo
            ->prepare('INSERT INTO uploads (title, tag, created_by, files) VALUES (?, ?, ?, ?)')
            ->execute([$iu->getAlt(), $iu->getTag()->name, $iu->getCreatedBy(), $iu->getCollection()]);
    }
}
