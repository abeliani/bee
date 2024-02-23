<?php

namespace Abeliani\Blog\Application\Enum;

enum AuthRequestAttrs: string
{
    case UserId = 'uid';
    case CurrentUser = 'current_user';
}
