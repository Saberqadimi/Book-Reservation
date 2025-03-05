<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReserveRequest;
use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Support\Facades\Response;

class ReservationController extends Controller
{
    const RESERVE_COMPLETE = 'رزرو تکمیل شد';
    protected ReservationService $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    public function index()
    {
        return $this->reservationService->list();
    }

    public function store(ReserveRequest $request)
    {
        $user = auth()->user();
        try {
            $reservation = $this->reservationService->createReservation($user, $request);
            return Response::successJson($reservation, 201);
        } catch (\Exception $e) {

            return Response::errorJson($e->getMessage(), 400);
        }
    }

    public function complete(Reservation $reservation)
    {
        try {
            $this->reservationService->completeReservation($reservation);
            return Response::successJson($reservation, 200);
        } catch (\Exception $e) {
            return Response::errorJson($e->getMessage(), 400);
        }
    }
}
