<?php

namespace App\Policies;

use App\Models\BookCopy;
use App\Models\User;

class BookCopyPolicy
{
    public function store(User $user)
    {
        return $user->hasRole('admin');
    }

    public function update(User $user)
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user)
    {
        return $user->hasRole('admin');
    }
}
