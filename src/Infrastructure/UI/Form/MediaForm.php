<?php
declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\UI\Form;

use GuzzleHttp\Psr7\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Abeliani\Blog\Infrastructure\Service\RequestValidator\Validator as MyAssert;

final class MediaForm
{
    #[MyAssert\Image(
        maxSize: '4M',
        mimeTypes: ['image/jpeg', 'image/png', 'image/gif'],
        maxSizeMessage: 'The image is too large ({{ size }} {{ suffix }}). Maximum allowed size is {{ limit }} {{ suffix }}.',
        mimeTypesMessage: 'Please upload a valid image (JPEG, PNG, GIF).',
    )]
    private ?UploadedFile $image;
    #[Assert\NotBlank]
    #[Assert\Length(min: 50)]
    private string $image_data;
    private string $video;

    public function getImage(): ?UploadedFile
    {
        return $this->image;
    }

    public function getImageData(): string
    {
        return $this->image_data;
    }

    public function getVideo(): string
    {
        return $this->video;
    }
}
