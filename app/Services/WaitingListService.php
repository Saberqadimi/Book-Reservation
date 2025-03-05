<?php

namespace App\Services;

use App\Models\User;
use App\Models\WaitingList;

class WaitingListService
{
    public function addToWaitingList(User $user, int $bookId)
    {
        if (!WaitingList::where('user_id', $user->id)->where('book_id', $bookId)->exists()) {
            WaitingList::create([
                'user_id' => $user->id,
                'book_id' => $bookId
            ]);
        }
    }

    public function getNextUser(int $bookId): ?WaitingList
    {
        return WaitingList::where('book_id', $bookId)
            ->join('users', 'waiting_lists.user_id', '=', 'users.id')
            ->orderByRaw("CASE WHEN users.membership_type = 'vip' THEN 1 ELSE 2 END")
            ->orderBy('waiting_lists.created_at', 'asc')
            ->select('waiting_lists.*')
            ->first();
    }

    public function removeFromWaitingList(User $user, int $bookId):void
    {
        WaitingList::where('user_id', $user->id)
            ->where('book_id', $bookId)
            ->delete();
    }

    public function increaseRetryCount(User $user, int $bookId) :void
    {
        $waitingEntry = WaitingList::where('user_id', $user->id)
            ->where('book_id', $bookId)
            ->first();

        if ($waitingEntry) {
            $waitingEntry->increment('retry_count');

            if ($waitingEntry->retry_count >= 3) {
                $this->removeFromWaitingList($user, $bookId);
            }
        }
    }

}
