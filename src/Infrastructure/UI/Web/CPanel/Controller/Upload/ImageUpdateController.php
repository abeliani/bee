<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\UI\Web\CPanel\Controller\Upload;

use Abeliani\Blog\Application\Middleware\JwtAuthenticationMiddleware;
use Abeliani\Blog\Application\Service\Upload\UploadService;
use Abeliani\Blog\Domain\Entity\Image;
use Abeliani\Blog\Domain\Exception;
use Abeliani\Blog\Domain\Repository\Upload\ImageRepositoryInterface;
use Abeliani\Blog\Infrastructure\Middleware\WithMiddleware;
use Abeliani\Blog\Infrastructure\Service\AuthService;
use Abeliani\Blog\Infrastructure\Service\Form\FormService;
use Abeliani\Blog\Infrastructure\UI\Web\CPanel\Form\ImageUploadForm;
use Psr\Http\Message;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;

#[WithMiddleware(JwtAuthenticationMiddleware::class)]
final readonly class ImageUpdateController implements RequestHandlerInterface
{
    public function __construct(
        private Environment $view,
        private FormService $formService,
        private Message\ResponseInterface $response,
        private UploadService $service,
        private ImageRepositoryInterface $repository,
    ) {
    }

    public function handle(Message\ServerRequestInterface $request): Message\ResponseInterface
    {
        $formInspector = $this->formService->buildInspector($request, ImageUploadForm::class);
        /** @var ImageUploadForm $form */
        $form = $formInspector->getForm();
        $actor = AuthService::extractUser($request);

        if (!$formInspector->validate('id')
            || !($image = $this->repository->find($form->getId()))) {
            throw new Exception\NotFoundException('Image not found');
        }

        if (!$formInspector->isEmptyForm() && $formInspector->validate()) {
            // todo remove old images from $image
            $this->service->upload($actor, $form);

            return $this->response
                ->withStatus(302)
                ->withHeader('Location', '/cpanel/article');
        }

        if ($formInspector->isEmptyForm()) {
            $form = [
                'id' => $image->getId(),
                'alt' => $image->getAlt(),
                'loaded' => $image->getCollection()
                    ->stream()
                    ->filter(fn (Image $image) => $image->getType() === 'view')
                    ->getCollection()
                    ->current()
            ];
        } else {
            $form = $form->toArray();
        }

        $render = $this->view->render('cpanel/image_create.twig', array_merge($form, [
            'section' => 'Update article',
            'action_url' => '/cpanel/image/article/' . $form['id'],
            'errors' => $formInspector->getValidateErrors(),
        ]));

        $this->response->getBody()->write($render);
        return $this->response;
    }
}
