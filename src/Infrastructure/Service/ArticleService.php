<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Service;

use Abeliani\Blog\Application\Service\Image\Processor\Handler\UploadedFileHandler;
use Abeliani\Blog\Application\Service\Image\Processor\ImageQueryProcessor;
use Abeliani\Blog\Application\Service\Image\Processor\ProcessorContext;
use Abeliani\Blog\Application\Service\Image\Processor\SavePathPremakeProcessor;
use Abeliani\Blog\Domain\Collection\Concrete\ImageCollection;
use Abeliani\Blog\Domain\Entity\Image;
use Abeliani\Blog\Domain\Exception\ArticleException;
use Abeliani\Blog\Domain\Factory\ArticleFactory;
use Abeliani\Blog\Domain\Model\Article;
use Abeliani\Blog\Domain\Model\User;
use Abeliani\Blog\Domain\Repository\Article as repo;
use Abeliani\Blog\Infrastructure\UI\Form\ArticleForm;

final readonly class ArticleService
{
    public function __construct(
        private repo\CreateRepositoryInterface $article,
        private repo\UpdateRepositoryInterface $updateRepository,
        private ImageQueryProcessor            $imageQueryProcessor,
        private SavePathPremakeProcessor       $imagePathsProcessor,
        private string                         $uploadDir,
    ) {
    }

    /**
     * @throws \ImagickException
     * @throws ArticleException
     */
    public function create(User $actor, ArticleForm $form): void
    {
        if ($form->getMedia()->getImage()->getError() === UPLOAD_ERR_OK) {
            register_shutdown_function(fn () =>
                $this->imageQueryProcessor->process(new ProcessorContext([
                    'width' => $form->getMedia()->getImageData()->getWidth(),
                    'height' => $form->getMedia()->getImageData()->getHeight(),
                    'x' => $form->getMedia()->getImageData()->getX(),
                    'y' => $form->getMedia()->getImageData()->getY(),
                ]),  new UploadedFileHandler($form->getMedia()->getImage())
            ));
        }

        $article = ArticleFactory::create(
            $actor->getId(),
            $form->getCategoryId(),
            $form->getTitle(),
            $form->getSlug(),
            $form->getPreview(),
            $form->getContent(),
            $form->getTags(),
            (string) $this->getImagesData(),
            $form->getMedia()->getImageAlt(),
            $form->getMedia()->getVideo(),
            $form->getSeo()->jsonSerialize(),
            $form->getOg()->jsonSerialize(),
            $form->getLanguage(),
            $form->getStatus(),
        );

        $this->article->create($article);
    }

    /**
     * @param User $user
     * @param Article $article
     * @param ArticleForm $form
     * @return void
     * @throws ArticleException
     * @throws \ImagickException
     */
    public function update(User $user, Article $article, ArticleForm $form): void
    {
        if ($form->getMedia()->getImage()->getError() === UPLOAD_ERR_OK) {
            foreach($article->getImages() as $image) {
                if ($image->getType() === 'original') {
                    continue;
                }

                $file = dirname($this->uploadDir) . $image->getUrl();
                file_exists($file) and unlink($file);
            }

            register_shutdown_function(fn () =>
                $this->imageQueryProcessor->process(new ProcessorContext([
                    'width' => $form->getMedia()->getImageData()->getWidth(),
                    'height' => $form->getMedia()->getImageData()->getHeight(),
                    'x' => $form->getMedia()->getImageData()->getX(),
                    'y' => $form->getMedia()->getImageData()->getY(),
                ]),  new UploadedFileHandler($form->getMedia()->getImage())
            ));

            $imageData = $this->getImagesData();
        }

        $updated = ArticleFactory::createFromForm(
            $user->getId(),
            $article->getCreatedBy(),
            $article->getCreatedAt(),
            $article->getViewCount(),
            $form,
            $imageData ?? $article->getImages(),
        );

        $this->updateRepository->update($updated);
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