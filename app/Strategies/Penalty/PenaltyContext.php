<?php

namespace App\Strategies\Penalty;

use App\Contracts\PenaltyStrategy;
use App\Models\User;
class PenaltyContext
{
    private PenaltyStrategy $strategy;

    public function __construct(User $user)
    {
        $strategies = [
            'vip' => new VipPenalty(),
            'regular' => new RegularPenalty(),
        ];

        if (!array_key_exists($user->membership_type, $strategies)) {
            throw new \InvalidArgumentException("نوع عضویت نامعتبر است: {$user->membership_type}");
        }

        $this->strategy = $strategies[$user->membership_type];
    }

    public function calculate(int $daysLate, int $previousPenalties): float
    {
        return $this->strategy->calculatePenalty($daysLate, $previousPenalties);
    }
}
