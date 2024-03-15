<?php

namespace Abeliani\Blog\Domain\Enum;

enum SubscriptionStatus: int
{
    case Pending = 0;
    case Active = 1;
    case Canceled = 2;
    case Block = 3;
}
