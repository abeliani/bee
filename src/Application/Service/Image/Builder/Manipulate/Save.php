<?php
declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Builder\Manipulate;

use Abeliani\Blog\Application\Service\Image\ManipulateEnum;
use Abeliani\Blog\Application\Service\Image\Processor\Manipulate\GD\SaveGdImage;
use Abeliani\Blog\Application\Service\Image\Processor\Manipulate\Imagick\SaveImagick;

#[SaveGdImage]
#[SaveImagick]
class Save extends Manipulate
{
    private string $format;

    public function __construct(
        private readonly string $path,
        int $mimeType = IMAGETYPE_JPEG,
        ?string $library = null,
    ) {
        $this->format = $this->getFormatByMimeType($mimeType);
        parent::__construct($library);
    }

    public static function getName(): string
    {
        return ManipulateEnum::save->name;
    }

    public function getPath(): string
    {
        return sprintf('%s.%s', $this->path, $this->format);
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function getValue(): array
    {
        return [
            'path' => $this->getPath(),
            'format' => $this->getFormat(),
        ];
    }

    protected function getFormatByMimeType(int $mimeTypeConstant): string
    {
        return match ($mimeTypeConstant) {
            IMAGETYPE_GIF => 'gif',
            IMAGETYPE_JPEG => 'jpeg',
            IMAGETYPE_PNG => 'png',
            IMAGETYPE_BMP => 'bmp',
            IMAGETYPE_WEBP => 'webp',
            IMAGETYPE_TIFF_II, IMAGETYPE_TIFF_MM => 'tif',
            default => throw new \LogicException('Unrecognized MIME type'),
        };
    }
}
