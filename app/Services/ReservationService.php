<?php

namespace App\Services;

use App\Http\Resources\ReservationResource;
use App\Models\BookCopy;
use App\Models\Reservation;
use App\ReservationTimePenalty;
use App\Traits\UserReservation;
use Carbon\Carbon;
use Exception as ExceptionAlias;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ReservationService
{
    use UserReservation;

    const ADD_IN_WAITING_LIST = "هیچ نسخه ای از کتاب موجود نبود شما به لیست انتظار برای کتاب مورد نظر اضافه شدین.";
    private WaitingListService $waitingListService;

    public function __construct(WaitingListService $waitingListService)
    {
        $this->waitingListService = $waitingListService;
    }

    public function list()
    {
        return Cache::remember('reserved_books', 600, function () {
            return new ReservationResource(
                QueryBuilder::for(Reservation::class)
                    ->with(['bookCopy.book', 'user'])
                    ->allowedFilters([
                        AllowedFilter::scope('user'),
                        AllowedFilter::scope('book'),
                        AllowedFilter::exact('status'),
                    ])
                    ->roleCheck()
                    ->latest()
                    ->paginate(request('per_page', 12))
            );
        });
    }

    /**
     * @throws ExceptionAlias
     */
    public function createReservation($user, $request)
    {
        return DB::transaction(function () use ($user, $request) {
            $bookCopy = $this->getAvailableBookCopy($request->book_id);
            if (!$bookCopy) {
                $this->waitingListService->addToWaitingList($user, $request->book_id);
                return Response::successJson(static::ADD_IN_WAITING_LIST, 404);
            }

            $bookCopy->update(['status' => 'reserved']);
            $returnDate = $request->has('return_date')
                ? Carbon::parse($request->return_date)->toDateString()
                : now()->addDays(ReservationTimePenalty::GRACE_PERIOD->value)->toDateString();

            return $this->manualReserve($user, $bookCopy->book_id, $bookCopy->id, $returnDate);
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
        });
    }

    private function getAvailableBookCopy($bookId)
    {
        return BookCopy::where('book_id', $bookId)
            ->where('status', 'available')
            ->lockForUpdate()
            ->first();
    }

    private function markReservationAsCompleted(Reservation $reservation, BookCopy $bookCopy)
    {
        $bookCopy->update(['status' => 'available']);
        $reservation->update(['status' => 'completed']);
    }

}
