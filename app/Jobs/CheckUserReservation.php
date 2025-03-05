<?php

namespace App\Jobs;

use App\Models\Reservation;
use App\Models\User;
use App\Services\WaitingListService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckUserReservation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private User $user;
    private int $bookId;

    public function __construct(User $user, int $bookId)
    {
        $this->user = $user;
        $this->bookId = $bookId;
    }

    public function handle(WaitingListService $waitingListService)
    {
        $existingActiveReservation = Reservation::where('user_id', $this->user->id)
            ->where('book_id', $this->bookId)
            ->where('status', 'active')
            ->exists();

        if (!$existingActiveReservation) {
            $waitingListService->increaseRetryCount($this->user, $this->bookId);
        }
    }

}
