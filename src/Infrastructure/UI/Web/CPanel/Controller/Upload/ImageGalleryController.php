<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\UI\Web\CPanel\Controller\Upload;

use Abeliani\Blog\Application\Middleware\JwtAuthenticationMiddleware;
use Abeliani\Blog\Domain\Entity\Image;
use Abeliani\Blog\Domain\Enum\UploadTag;
use Abeliani\Blog\Domain\Model\ImageUpload;
use Abeliani\Blog\Domain\Repository\Upload\ImageRepositoryInterface;
use Abeliani\Blog\Infrastructure\Middleware\WithMiddleware;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[WithMiddleware(JwtAuthenticationMiddleware::class)]
final readonly class ImageGalleryController implements RequestHandlerInterface
{
    public function __construct(
        private ResponseInterface $response,
        private ImageRepositoryInterface $repository,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        foreach ($this->repository->findAll(UploadTag::article) as $images) {
            /** @var ImageUpload $images */
            foreach ($images->getCollection() as $image) {
                /** @var Image $image */
                $result[] = [
                    'src' => $image->getUrl(),
                    'tag' => $image->getType(),
                    'alt' => $images->getAlt(),
                    'name' => substr($images->getAlt(), 0, 25),
                ];
            }
        }

        return $this->response
            ->withBody(Utils::streamFor(json_encode(['result' => $result ?? []])))
            ->withHeader('Content-Type', 'application/json');
    }
}
