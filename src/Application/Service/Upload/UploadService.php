<?php

declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Upload;

use Abeliani\Blog\Application\Service\Image\Processor\Handler\UploadedFileHandler;
use Abeliani\Blog\Application\Service\Image\Processor\ImageQueryProcessor;
use Abeliani\Blog\Application\Service\Image\Processor\SavePathPremakeProcessor;
use Abeliani\Blog\Domain\Collection\Concrete\ImageCollection;
use Abeliani\Blog\Domain\Entity\Image;
use Abeliani\Blog\Domain\Enum\UploadTag;
use Abeliani\Blog\Domain\Model\ImageUpload;
use Abeliani\Blog\Domain\Model\User;
use Abeliani\Blog\Domain\Repository\Upload\ImageRepositoryInterface;
use Abeliani\Blog\Infrastructure\UI\Web\CPanel\Form\ImageUploadForm;

final readonly class UploadService
{
    public function __construct(
        private ImageQueryProcessor $imageQueryProcessor,
        private SavePathPremakeProcessor $imagePathsProcessor,
        private ImageRepositoryInterface $repository,
        private string $uploadDir,
    ) {
    }

    public function upload(User $actor, ImageUploadForm $form): void
    {
        if ($form->getImage()->getError() === UPLOAD_ERR_OK) {
            register_shutdown_function(
                fn () => $this->imageQueryProcessor->process(null,  new UploadedFileHandler($form->getImage()))
            );
        }

        $this->repository->save(
            new ImageUpload(null, $form->getAlt(), $actor->getId(), UploadTag::article, $this->getImagesData())
        );
    }

    public function getImagesData(): ImageCollection
    {
        $collection = new ImageCollection;
        $storage = dirname($this->uploadDir);
        foreach ($this->imagePathsProcessor->process() as $type => $absolute) {
            $collection->add(new Image($type, str_replace($storage, '', $absolute)));
        }

        return $collection;
    }
}
