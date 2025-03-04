<?php

namespace App\Contracts;

interface PenaltyStrategy
{
    public function calculatePenalty(int $daysLate, int $previousPenalties): float;
}
