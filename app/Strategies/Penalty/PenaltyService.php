<?php

namespace App\Strategies\Penalty;

use Carbon\Carbon;

class PenaltyService
{
    public function checkInfoAndApplyPenalty($reservation)
    {
        $user = $reservation->user;
        $returnDate = Carbon::parse($reservation->return_date);
        $actualReturnDate = Carbon::parse($reservation->updated_at);
        $daysLate = $returnDate->diffInDays($actualReturnDate);

        if ($daysLate > 0) {
            $this->applyPenalty($user, $daysLate);
        }

    }

    private function applyPenalty($user, $daysLate)
    {
        $penaltyContext = new PenaltyContext($user);
        $penaltyAmount = $penaltyContext->calculate($daysLate, $user->late_returns_count);
        $user->update([
            'score' => max($user->score - $penaltyAmount, 0),
            'late_returns_count' => $user->late_returns_count + 1
        ]);

    }

}
