<?php

namespace App\Listeners;

use App\Events\BookReturned;
use App\Jobs\CheckUserReservation;
use App\Models\Reservation;
use App\Notifications\ReservationWaitingList;
use App\ReservationTimePenalty;
use App\Services\WaitingListService;
use App\Traits\UserReservation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotifyNextUser implements ShouldQueue
{
    use UserReservation;

    private WaitingListService $waitingListService;

    public function __construct(
        WaitingListService $waitingListService
    )
    {
        $this->waitingListService = $waitingListService;
    }

    public function handle(BookReturned $event)
    {
        $bookCopy = $event->bookCopy;
        $nextUser = $this->waitingListService->getNextUser($bookCopy->book_id);

        if ($nextUser) {
            try {
                $returnDate = now()->addDays(ReservationTimePenalty::GRACE_PERIOD->value)->toDateString();
                $reservation = $this->manualReserve($nextUser->user, $bookCopy->book_id, $bookCopy->id, $returnDate);
                if ($reservation instanceof Reservation) {
                    $bookCopy->update(['status' => 'reserved']);
                    $this->waitingListService->removeFromWaitingList($nextUser->user, $bookCopy->book_id);
                }
            } catch (\Exception $e) {
                Log::error("Error during reservation for user {$nextUser->user_id} on book {$bookCopy->book_id}: " . $e->getMessage());
                Notification::send($nextUser->user, new ReservationWaitingList($bookCopy));
                //add delay for 24 o'clock check user reservation after user was in waitingList
                CheckUserReservation::dispatch($nextUser->user, $bookCopy->book_id);//->delay(now()->addHours(24));
            }
        }
    }
}

