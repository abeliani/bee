<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\UI\Web\CPanel\Controller\Upload;

use Abeliani\Blog\Application\Middleware\JwtAuthenticationMiddleware;
use Abeliani\Blog\Application\Service\Upload\UploadService;
use Abeliani\Blog\Infrastructure\Middleware\WithMiddleware;
use Abeliani\Blog\Infrastructure\Service\AuthService;
use Abeliani\Blog\Infrastructure\Service\Form\FormService;
use Abeliani\Blog\Infrastructure\UI\Web\CPanel\Form\ImageUploadForm;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;
use Twig\Error;

#[WithMiddleware(JwtAuthenticationMiddleware::class)]
final readonly class ImageCreateController implements RequestHandlerInterface
{
    public function __construct(
        private Environment $view,
        private FormService $formService,
        private ResponseInterface $response,
        private UploadService $service,
    ) {
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws Error\LoaderError
     * @throws Error\RuntimeError
     * @throws Error\SyntaxError
     * @throws \JsonException
     * @throws \ReflectionException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $formInspector = $this->formService->buildInspector($request, ImageUploadForm::class);

        if (!$formInspector->isEmptyForm() && $formInspector->validate()) {
            /** @var ImageUploadForm $form */
            $form = $formInspector->getForm();
            $this->service->upload(AuthService::extractUser($request), $form);

            return $this->response
                ->withStatus(302)
                ->withHeader('Location', '/cpanel/image');
        }

        $render = $this->view->render('cpanel/image_create.twig', array_merge($formInspector->formToArray(), [
            'section' => 'Upload image',
            'action_url' => '/cpanel/image/article/upload',
            'errors' => $formInspector->getValidateErrors(),
        ]));

        $this->response->getBody()->write($render);
        return $this->response;
    }
}
