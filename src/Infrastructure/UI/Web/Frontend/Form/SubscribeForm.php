<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\UI\Web\Frontend\Form;

use Abeliani\Blog\Domain\Interface\ToArrayInterface;
use Abeliani\Blog\Infrastructure\Service\RequestValidator\Validator\EmailMx;
use Symfony\Component\Validator\Constraints as Assert;

final class SubscribeForm implements ToArrayInterface
{
    #[Assert\NotBlank]
    #[Assert\Email]
    #[EmailMx]
    private string $subscriber;

    public function getSubscriber(): string
    {
        return $this->subscriber;
    }

    public function toArray(): array
    {
        return [
            'subscriber' => $this->subscriber,
        ];
    }
}
