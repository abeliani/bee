<?php
declare(strict_types=1);

namespace Abeliani\Blog\Infrastructure\UI\Form;

use Symfony\Component\Validator\Constraints as Assert;

final class CropperData
{
    #[Assert\NotBlank]
    #[Assert\Type("numeric")]
    private float $x;

    #[Assert\NotBlank]
    #[Assert\Type("numeric")]
    private float $y;

    #[Assert\NotBlank]
    #[Assert\Type("numeric")]
    #[Assert\GreaterThan(value: 0)]
    private float $width;

    #[Assert\NotBlank]
    #[Assert\Type("numeric")]
    #[Assert\GreaterThan(value: 0)]
    private float $height;

    #[Assert\Type("numeric")]
    private float $rotate;

    #[Assert\Type("numeric")]
    private float $scaleX;

    #[Assert\Type("numeric")]
    private float $scaleY;

    public function getX(): float
    {
        return $this->x;
    }

    public function getY(): float
    {
        return $this->y;
    }

    public function getWidth(): float
    {
        return $this->width;
    }

    public function getHeight(): float
    {
        return $this->height;
    }

    public function getRotate(): float
    {
        return $this->rotate;
    }

    public function getScaleX(): float
    {
        return $this->scaleX;
    }

    public function getScaleY(): float
    {
        return $this->scaleY;
    }
}
