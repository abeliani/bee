<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\UI\Web\CPanel\Controller\Upload;

use Abeliani\Blog\Application\Middleware\JwtAuthenticationMiddleware;
use Abeliani\Blog\Domain\Enum\UploadTag;
use Abeliani\Blog\Domain\Repository\Upload\ImageRepositoryInterface;
use Abeliani\Blog\Infrastructure\Middleware\WithMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;

#[WithMiddleware(JwtAuthenticationMiddleware::class)]
final readonly class ImageIndexController implements RequestHandlerInterface
{
    public function __construct(
        private Environment $view,
        private ResponseInterface $response,
        private ImageRepositoryInterface $repository,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $render = $this->view->render('cpanel/image_index.twig', [
            'section' => 'Images index',
            'images' => $this->repository->findAll(UploadTag::article),
        ]);

        $this->response->getBody()->write($render);
        return $this->response;
    }
}
