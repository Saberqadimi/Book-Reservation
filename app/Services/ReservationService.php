<?php

namespace App\Services;

use App\Http\Resources\ReservationResource;
use App\Models\BookCopy;
use App\Models\Reservation;
use App\ReservationTimePenalty;
use Carbon\Carbon;
use Exception as ExceptionAlias;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ReservationService
{
    public function list()
    {
        return new ReservationResource(
            QueryBuilder::for(Reservation::class)
                ->with(['bookCopy.book', 'user'])
                ->allowedFilters([
                    AllowedFilter::scope('user'),
                    AllowedFilter::scope('book'),
                    AllowedFilter::exact('status'),
                ])
                ->latest()
                ->paginate(request('per_page', 12))
        );
    }

    /**
     * @throws ExceptionAlias
     */
    public function createReservation($user, $request)
    {
        $this->validateUser($user);

        return DB::transaction(function () use ($user, $request) {
            $bookCopy = $this->getAvailableBookCopy($request->book_id);
            $status = $bookCopy ? 'active' : 'pending';
            $returnDate = $request->has('return_date')
                ? Carbon::parse($request->return_date)->toDateString()
                : now()->addDays(ReservationTimePenalty::GRACE_PERIOD->value)->toDateString();

            $reservation = Reservation::create([
                'user_id' => $user->id,
                'book_id' => $request->book_id,
                'book_copy_id' => $bookCopy?->id,
                'status' => $status,
                'return_date' => $bookCopy ? $returnDate : null
            ]);

            if ($bookCopy) {
                $bookCopy->update(['status' => 'reserved']);
            }

            return $reservation;
        });
    }

    public function completeReservation(Reservation $reservation)
    {
        return DB::transaction(function () use ($reservation) {
            $bookCopy = $reservation->bookCopy;

            if (!$bookCopy) {
                throw new ExceptionAlias("نسخه کتاب یافت نشد.");
            }

            $this->markReservationAsCompleted($reservation, $bookCopy);
            $this->assignBookToNextReservation($bookCopy);
        });
    }

    private function validateUser($user)
    {
        if ($user->score < 50) {
            throw new ExceptionAlias("کاربر امتیاز کافی برای رزرو ندارد.");
        }
    }

    private function getAvailableBookCopy($bookId)
    {
        return BookCopy::where('book_id', $bookId)
            ->where('status', 'available')
            ->first();
    }

    private function markReservationAsCompleted(Reservation $reservation, BookCopy $bookCopy)
    {
        $bookCopy->update(['status' => 'available']);
        $reservation->update(['status' => 'completed']);
    }

    private function assignBookToNextReservation(BookCopy $bookCopy)
    {
        $nextReservation = Reservation::whereNull('book_copy_id')
            ->where('status', 'pending')
            ->where('book_id', $bookCopy->book_id)
            ->leftJoin('users', 'reservations.user_id', '=', 'users.id')
            ->orderByRaw("CASE WHEN users.membership_type = 'vip' THEN 1 ELSE 2 END")
            ->orderBy('reservations.created_at')
            ->select('reservations.*')
            ->first();

        if ($nextReservation) {
            $nextReservation->update([
                'book_copy_id' => $bookCopy->id,
                'status' => 'active'
            ]);
            $bookCopy->update(['status' => 'reserved']);
        }
    }
}
