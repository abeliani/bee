<?php

namespace Abeliani\Blog\Application\Enum;

enum ConfigDi
{
    case ArticleImageBuilder;
    case ArticleImageProcessor;
    case CategoryImageProcessor;
    case UploadImageProcessor;
    case CategoryImageBuilder;
    case UploadImageBuilder;
}
