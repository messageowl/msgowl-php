<?php

namespace MessageOwl\Enums;

enum SmsStatus: int
{
    case Delivered = 1;
    case Failed    = 5;
    case Invalid   = 6;
}
