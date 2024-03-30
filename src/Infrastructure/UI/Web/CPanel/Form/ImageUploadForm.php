<?php

declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\UI\Web\CPanel\Form;

use Abeliani\Blog\Domain\Interface\ToArrayInterface;
use Abeliani\Blog\Infrastructure\Service\RequestValidator\Validator as MyAssert;
use GuzzleHttp\Psr7\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

final class ImageUploadForm implements ToArrayInterface
{
    #[Assert\Type('integer')]
    private int $id;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    private string $alt;

    #[Assert\NotBlank]
    #[MyAssert\Image(
        maxSize: '5M',
        mimeTypes: ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg'],
        maxSizeMessage: 'The image is too large ({{ size }} {{ suffix }}). Maximum allowed size is {{ limit }} {{ suffix }}.',
        mimeTypesMessage: 'Please upload a valid image (JPEG, PNG, GIF, WEBP, SVG).',
    )]
    private ?UploadedFile $image;

    public function getId(): int
    {
        return $this->id;
    }

    public function getAlt(): string
    {
        return $this->alt;
    }

    public function getImage(): ?UploadedFile
    {
        return $this->image;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'alt' => $this->alt,
            'image' => $this->image,
        ];
    }
}
