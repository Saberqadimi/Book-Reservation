<?php

namespace App;

enum ReservationTimePenalty: int
{
    case GRACE_PERIOD = 7;
    case VIP_PENALTY_PER_DAY = 4;
    case VIP_PREVIOUS_PENALTY = 2;
    case REGULAR_PENALTY_PER_DAY = 6;
    case REGULAR_PREVIOUS_PENALTY = 5;
}
