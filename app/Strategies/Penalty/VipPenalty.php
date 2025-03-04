<?php

namespace App\Strategies\Penalty;

use App\Contracts\PenaltyStrategy;
use App\ReservationTimePenalty;

class VipPenalty implements PenaltyStrategy
{
    public function calculatePenalty(int $daysLate, int $previousPenalties): float
    {
        if ($daysLate <= ReservationTimePenalty::GRACE_PERIOD->value) return 0;

        return ($daysLate * ReservationTimePenalty::VIP_PENALTY_PER_DAY->value) +
            ($previousPenalties * ReservationTimePenalty::VIP_PREVIOUS_PENALTY->value);
    }
}
