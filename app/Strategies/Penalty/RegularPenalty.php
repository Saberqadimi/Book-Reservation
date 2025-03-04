<?php

namespace App\Strategies\Penalty;

use App\Contracts\PenaltyStrategy;
use App\ReservationTimePenalty;

class RegularPenalty implements PenaltyStrategy
{
    public function calculatePenalty(int $daysLate, int $previousPenalties): float
    {
        return ($daysLate * ReservationTimePenalty::REGULAR_PENALTY_PER_DAY->value) +

            ($previousPenalties * ReservationTimePenalty::REGULAR_PREVIOUS_PENALTY->value);
    }
}
