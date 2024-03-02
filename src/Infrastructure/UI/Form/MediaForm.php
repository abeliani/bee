<?php
declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\UI\Form;

use GuzzleHttp\Psr7\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Abeliani\Blog\Infrastructure\Service\RequestValidator\Validator as MyAssert;

final class MediaForm
{
    #[MyAssert\Image(
        maxSize: '5M',
        mimeTypes: ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg'],
        maxSizeMessage: 'The image is too large ({{ size }} {{ suffix }}). Maximum allowed size is {{ limit }} {{ suffix }}.',
        mimeTypesMessage: 'Please upload a valid image (JPEG, PNG, GIF).',
    )]
    private ?UploadedFile $image;

    #[Assert\Valid]
    private CropperData $imageData;

    #[Assert\NotBlank]
    private string $imageTitle;

    #[Assert\Url]
    private string $video;

    public function getImage(): ?UploadedFile
    {
        return $this->image;
    }

    public function getImageData(): CropperData
    {
        return $this->imageData;
    }

    public function getImageAlt(): string
    {
        return $this->imageTitle;
    }

    public function getVideo(): string
    {
        return $this->video;
    }
}
