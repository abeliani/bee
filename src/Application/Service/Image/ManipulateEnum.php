<?php

namespace Abeliani\Blog\Application\Service\Image;

enum ManipulateEnum
{
    case adaptiveResize;
    case convert;
    case crop;
    case resize;
    case save;
    case strip;
}
