<?php

namespace App\Observers;

use App\Models\Reservation;
use App\ReservationTimePenalty;
use App\Strategies\Penalty\PenaltyContext;
use Carbon\Carbon;
use http\Exception\InvalidArgumentException;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

class ReservationObserver
{
    public function updated(Reservation $reservation)
    {
        if ($reservation->isDirty('status') && $reservation->status === 'completed') {
            $user = $reservation->user;
            $returnDate = Carbon::parse($reservation->return_date);
            $actualReturnDate = Carbon::parse($reservation->updated_at);
            $daysLate = $returnDate->diffInDays($actualReturnDate);

            if ($daysLate > 0) {
                $this->applyPenalty($user, $daysLate);
            }
        }
    }

    private function applyPenalty($user, $daysLate)
    {
        $penaltyContext = new PenaltyContext($user);
        $penaltyAmount = $penaltyContext->calculate($daysLate, $user->late_returns_count);
        $user->update(['score' => max($user->score - $penaltyAmount, 0), 'late_returns_count', $user->late_returns_count + 1]);
    }


}
