<?php

declare(strict_types=1);

namespace Abeliani\Blog\Application\Service\Image\Processor\Filter\GD;

use Abeliani\Blog\Application\Service\Image\Builder\BuilderActionInterface;
use Abeliani\Blog\Application\Service\Image\Builder\Filter\Discolor;
use Abeliani\Blog\Application\Service\Image\Processor\ProcessorInterface;

#[\Attribute(\Attribute::TARGET_CLASS)]
final readonly class DiscolorGdImage implements ProcessorInterface
{
    /**
     * @inheritDoc
     * @param \GdImage $image
     * @param Discolor $action
     */
    public function __invoke(mixed $image, BuilderActionInterface $action): \GdImage
    {
        $hasError = false;
        $width = imagesx($image);
        $height = imagesy($image);

        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                if (($rgb = imagecolorat($image, $x, $y)) === false) {
                    $hasError = true;
                    break;
                }
                $red = ($rgb >> 16) & 0xFF;
                $green = ($rgb >> 8) & 0xFF;
                $blue = $rgb & 0xFF;

                $gray = ($red + $green + $blue) / 3;

                $mixedRed = (int) ($action->getValue() * $red + (1 - $action->getValue()) * $gray);
                $mixedGreen = (int) ($action->getValue() * $green + (1 - $action->getValue()) * $gray);
                $mixedBlue = (int) ($action->getValue() * $blue + (1 - $action->getValue()) * $gray);

                if (($mixedColor = imagecolorallocate($image, $mixedRed, $mixedGreen, $mixedBlue)) === false) {
                    $hasError = true;
                    break;
                }
                if (!imagesetpixel($image, $x, $y, $mixedColor)) {
                    $hasError = true;
                    break;
                }
            }
        }
        if ($hasError) {
            throw new \RuntimeException('Failed to apply GdImage discolor filter');
        }

        return $image;
    }
}