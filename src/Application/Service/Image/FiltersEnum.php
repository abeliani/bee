<?php

namespace Abeliani\Blog\Application\Service\Image;

enum FiltersEnum
{
    case brightness;
    case contrast;
    case grayscale;
    case discolor;
    case negate;
    case quality;
}
