<?php

namespace MessageOwl\Enums;

enum DeliveryStatus: int
{
    case Delivered          = 1;
    case Scheduled          = 3;
    case Retry              = 5;
    case Failed             = 6;
    case DeliveredDuplicate = 7;
    case Scam               = 8;
}
