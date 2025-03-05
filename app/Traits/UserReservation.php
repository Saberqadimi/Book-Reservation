<?php

namespace App\Traits;

use App\Models\Reservation;

trait UserReservation
{

    const USER_DOESNT_HAVE_SCORE = "کاربر امتیاز کافی برای رزرو ندارد.";

    public function manualReserve($user, $bookId, $bookCopyId, $data)
    {
        if ($user->score < 50) {
            throw new \Exception(static::USER_DOESNT_HAVE_SCORE);
        }

        return Reservation::create([
            'user_id' => $user->id,
            'book_id' => $bookId,
            'book_copy_id' => $bookCopyId,
            'status' => 'active',
            'return_date' => $data
        ]);
    }

}
