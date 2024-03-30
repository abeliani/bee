<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\Service;

use Abeliani\Blog\Application\Service\Image\Processor\Handler\UploadedFileHandler;
use Abeliani\Blog\Application\Service\Image\Processor\ImageQueryProcessor;
use Abeliani\Blog\Application\Service\Image\Processor\ProcessorContext;
use Abeliani\Blog\Application\Service\Image\Processor\SavePathPremakeProcessor;
use Abeliani\Blog\Domain\Collection\Concrete\ImageCollection;
use Abeliani\Blog\Domain\Entity\Image;
use Abeliani\Blog\Domain\Entity\RedirectUrl;
use Abeliani\Blog\Domain\Enum\UrlProtocol;
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
        private ImageQueryProcessor $imageQueryProcessor,
        private SavePathPremakeProcessor $imagePathsProcessor,
        private Redirector $redirector,
        private string $uploadDir,
        private string $host,
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

        $pattern = sprintf('`(?<=href=")(?!%s)(https?)://([\w@:%%._+~#=/?&-]+)`i', preg_quote($this->host));
        $content = preg_replace_callback($pattern, function (array $m) {
            $hash = $this->redirector->make($m[0]);
            $protocol = array_filter(UrlProtocol::cases(), fn (UrlProtocol $case) => $case->name === $m[1])[0] ?? null;
            $this->redirector->save(new RedirectUrl($hash, $m[2], $protocol, false));

            return sprintf('%s/c/%s', $this->host, $hash);
        }, $form->getContent());

        $article = ArticleFactory::create(
            $actor->getId(),
            $form->getCategoryId(),
            $form->getTitle(),
            $form->getSlug(),
            $form->getPreview(),
            $content,
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

        $pattern = sprintf('`(?<=href=")(?!%s)(https?)://([\w@:%%._+~#=/?&-]+)`i', preg_quote($this->host));
        $content = preg_replace_callback($pattern, function (array $m) {
            if (($url = $m[0]) && str_starts_with($url, $this->host)) {
                return $url;
            }

            $protocol = array_filter(UrlProtocol::cases(), fn (UrlProtocol $case) => $case->name === $m[1])[0] ?? null;
            $hash = $this->redirector->make($url);
            $this->redirector->save(new RedirectUrl($hash, $m[2], $protocol, false));

            return sprintf('%s/c/%s', $this->host, $hash);
        }, $form->getContent());

        $updated = ArticleFactory::createFromForm(
            $user->getId(),
            $article->getCreatedBy(),
            $content,
            $article->getCreatedAt(),
            $article->getStatus(),
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