<?php

namespace App\Observers;

use App\Events\BookReturned;
use App\Models\Reservation;
use App\Strategies\Penalty\PenaltyContext;
use App\Strategies\Penalty\PenaltyService;
use Carbon\Carbon;

class ReservationObserver
{
    private PenaltyService $penaltyService;

    public function __construct(PenaltyService $penaltyService)
    {
        $this->penaltyService = $penaltyService;
    }

    public function updated(Reservation $reservation)
    {
        if ($reservation->isDirty('status') && $reservation->status === 'completed') {

            event(new BookReturned($reservation->bookCopy));
            $this->penaltyService->checkInfoAndApplyPenalty($reservation);
        }
    }



}
