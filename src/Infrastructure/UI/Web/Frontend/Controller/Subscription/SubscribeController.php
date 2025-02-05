<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\UI\Web\Frontend\Controller\Subscription;

use Abeliani\Blog\Application\Middleware\RateLimitMiddleware;
use Abeliani\Blog\Application\Service\Subscription\SubscriptionService;
use Abeliani\Blog\Domain\Repository\Article;
use Abeliani\Blog\Domain\Repository\Category;
use Abeliani\Blog\Domain\Repository\Tag;
use Abeliani\Blog\Infrastructure\Middleware\WithMiddleware;
use Abeliani\Blog\Infrastructure\Service\Form\FormService;
use Abeliani\Blog\Infrastructure\UI\Web\Frontend\Form\SubscribeForm;
use Psr\Http\Message;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;

#[WithMiddleware(RateLimitMiddleware::class)]
final readonly class SubscribeController implements RequestHandlerInterface
{
    public function __construct(
        private Environment $view,
        private FormService $formService,
        private Message\ResponseInterface $response,
        private Article\ReadRepositoryInterface $repository,
        private Category\ReadRepositoryInterface $categoryRepository,
        private Tag\ReadRepositoryInterface $tagRepository,
        private SubscriptionService $service,
    ) {
    }

    public function handle(Message\ServerRequestInterface $request): Message\ResponseInterface
    {
        $formInspector = $this->formService->buildInspector($request, SubscribeForm::class);

        if (!$formInspector->isEmptyForm() && $formInspector->validate()) {
            /** @var SubscribeForm $form */
            $form = $formInspector->getForm();
            $this->service->subscribe($form->getSubscriber());

            return $this->response
                ->withStatus(302)
                ->withHeader('Location', '/');
        }

        $this->response->getBody()->write($this->view->render('front/subscribe.twig', [
            'meta_lang' => getenv('APP_LANG'),
            'canonical' => sprintf('%s/subscribe', getenv('APP_HOST')),
            'meta_title' => getenv('SITE_NAME'),
            'meta_desc' => getenv('SITE_NAME'),
            'subscribe_page' => true,
            'last_articles' => $this->repository->findLast(),
            'categories' => $this->categoryRepository->findAll(),
            'tags' => $this->tagRepository->findAll(),
            // fixme pass google recaptcha secret as param
        ]));

        return $this->response;
    }
}