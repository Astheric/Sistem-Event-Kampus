<?php

namespace App\Enums;

enum CheckinResult: string
{
    case Success = 'SUCCESS';
    case AlreadyCheckedIn = 'ALREADY_CHECKED_IN';
    case Invalid = 'INVALID';
    case Unpaid = 'UNPAID';
    case OutsideWindow = 'OUTSIDE_WINDOW';
}
