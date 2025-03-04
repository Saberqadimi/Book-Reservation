<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReserveRequest;
use App\Models\Reservation;
use App\Models\User;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ReservationController extends Controller
{
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
            return response()->json(['message' => 'رزرو تکمیل شد']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
