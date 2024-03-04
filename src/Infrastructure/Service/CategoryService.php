<?php
declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Service;

use Abeliani\Blog\Application\Service\Image\Processor\Handler\UploadedFileHandler;
use Abeliani\Blog\Application\Service\Image\Processor\ImageQueryProcessor;
use Abeliani\Blog\Application\Service\Image\Processor\ProcessorContext;
use Abeliani\Blog\Application\Service\Image\Processor\SavePathPremakeProcessor;
use Abeliani\Blog\Domain\Exception\CategoryException;
use Abeliani\Blog\Domain\Factory\CategoryFactory;
use Abeliani\Blog\Domain\Model\Category;
use Abeliani\Blog\Domain\Model\Image;
use Abeliani\Blog\Domain\Model\User;
use Abeliani\Blog\Domain\Repository\Category\CreateCategoryRepositoryInterface;
use Abeliani\Blog\Domain\Repository\Category\UpdateCategoryRepositoryInterface;
use Abeliani\Blog\Infrastructure\UI\Form\CategoryForm;

readonly class CategoryService
{
    public function __construct(
        private CreateCategoryRepositoryInterface $categoryRepository,
        private UpdateCategoryRepositoryInterface $updateRepository,
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
        $category = CategoryFactory::create(
            $actor->getId(),
            $form->getTitle(),
            $form->getSlug(),
            $form->getContent(),
            $this->getImagesPaths(),
            $form->getMedia()->getImageAlt(),
            $form->getMedia()->getVideo(),
            $form->getSeo()->jsonSerialize(),
            [],
            $form->getLanguage(),
            $form->getStatus(),
        );

        $this->categoryRepository->create($category);

        register_shutdown_function(fn () =>
            $this->imageQueryProcessor->process(new ProcessorContext([
                'width' => $form->getMedia()->getImageData()->getWidth(),
                'height' => $form->getMedia()->getImageData()->getHeight(),
                'x' => $form->getMedia()->getImageData()->getX(),
                'y' => $form->getMedia()->getImageData()->getY(),
            ]),  new UploadedFileHandler($form->getMedia()->getImage())
        ));
    }

    /**
     * @throws CategoryException
     */
    public function update(User $user, Category $category, CategoryForm $form): void
    {
        if ($form->getMedia()->getImage()->getError() === UPLOAD_ERR_OK) {
            foreach($category->getImages() as $image) {
                if ($image->type === 'original') {
                    continue;
                }

                $file = dirname($this->uploadDir) . $image->url;
                file_exists($file) and unlink($file);
            }

            $images = $this->getImagesPaths();
        }

        $updated = CategoryFactory::createFull(
            $category->getId(),
            $form->getTitle(),
            $form->getSlug(),
            $form->getContent(),
            $images ?? $category->getImages(),
            $form->getMedia()->getImageAlt(),
            $form->getMedia()->getVideo(),
            $form->getSeo()->jsonSerialize(),
            [],
            $form->getLanguage(),
            $category->getCreatedBy(),
            $user->getId(),
            $form->getStatus(),
            $category->getCreatedAt(),
            new \DateTimeImmutable($form->getPublishedAt()),
            new \DateTimeImmutable(),
            $category->getDeletedAt(),
            $category->getViewCount()
        );

        $this->updateRepository->update($updated);

        register_shutdown_function(fn () =>
            $this->imageQueryProcessor->process(new ProcessorContext([
                'width' => $form->getMedia()->getImageData()->getWidth(),
                'height' => $form->getMedia()->getImageData()->getHeight(),
                'x' => $form->getMedia()->getImageData()->getX(),
                'y' => $form->getMedia()->getImageData()->getY(),
            ]),  new UploadedFileHandler($form->getMedia()->getImage())
        ));
    }

    public function getImagesPaths(): array
    {
        $storage = dirname($this->uploadDir);
        foreach ($this->imagePathsProcessor->process() as $type => $absolute) {
            $relative = str_replace($storage, '', $absolute);
            $images[] = new Image($type, $relative);
        }

        return $images ?? [];
    }
}