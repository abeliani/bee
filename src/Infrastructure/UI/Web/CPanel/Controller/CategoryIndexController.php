<?php declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\UI\Web\CPanel\Controller;

use Abeliani\Blog\Application\Middleware\JwtAuthenticationMiddleware;
use Abeliani\Blog\Domain\Repository\Category\ReadCategoryRepositoryInterface;
use Abeliani\Blog\Infrastructure\Middleware\WithMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;

#[WithMiddleware(JwtAuthenticationMiddleware::class)]
final readonly class CategoryIndexController implements RequestHandlerInterface
{
    public function __construct(
        private Environment $view,
        private ResponseInterface $response,
        private ReadCategoryRepositoryInterface $repository,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $render = $this->view->render('cpanel/category_index.twig', [
            'section' => 'Category index',
            'categories' => $this->repository->findAll(),
        ]);

        $this->response->getBody()->write($render);
        return $this->response;
    }
}
