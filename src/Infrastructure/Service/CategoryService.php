<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Service;

use Abeliani\Blog\Application\Service\Image\Processor\Handler\UploadedFileHandler;
use Abeliani\Blog\Application\Service\Image\Processor\ImageQueryProcessor;
use Abeliani\Blog\Application\Service\Image\Processor\ProcessorContext;
use Abeliani\Blog\Application\Service\Image\Processor\SavePathPremakeProcessor;
use Abeliani\Blog\Domain\Collection\Concrete\ImageCollection;
use Abeliani\Blog\Domain\Entity\Image;
use Abeliani\Blog\Domain\Exception\CategoryException;
use Abeliani\Blog\Domain\Factory\CategoryFactory;
use Abeliani\Blog\Domain\Model\Category;
use Abeliani\Blog\Domain\Model\User;
use Abeliani\Blog\Domain\Repository\Category as repo;
use Abeliani\Blog\Infrastructure\UI\Form\CategoryForm;

final readonly class CategoryService
{
    public function __construct(
        private repo\CreateCategoryRepositoryInterface $categoryRepository,
        private repo\UpdateCategoryRepositoryInterface $updateRepository,
        private ImageQueryProcessor               $imageQueryProcessor,
        private SavePathPremakeProcessor          $imagePathsProcessor,
        private string                            $uploadDir,
    ) {
    }

    /**
     * @throws \ImagickException
     * @throws CategoryException
     */
    public function create(User $actor, CategoryForm $form): void
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

        $category = CategoryFactory::create(
            $actor->getId(),
            $form->getTitle(),
            $form->getSlug(),
            $form->getContent(),
            (string) $this->getImagesData(),
            $form->getMedia()->getImageAlt(),
            $form->getSeo()->jsonSerialize(),
            $form->getOg()->jsonSerialize(),
            $form->getLanguage(),
            $form->getStatus(),
        );

        $this->categoryRepository->create($category);
    }

    /**
     * @throws CategoryException
     */
    public function update(User $user, Category $category, CategoryForm $form): void
    {
        if ($form->getMedia()->getImage()->getError() === UPLOAD_ERR_OK) {
            foreach($category->getImages() as $image) {
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

        $updated = CategoryFactory::createFromForm(
            $user->getId(),
            $category->getCreatedBy(),
            $category->getCreatedAt(),
            $form,
            $imageData ?? $category->getImages()
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