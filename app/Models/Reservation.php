<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'book_id' ,'book_copy_id', 'status'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bookCopy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BookCopy::class);
    }

    public function scopeUser($query, $search)
    {
        return $query->whereHas('user', function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%");
        });
    }

    public function scopeBook($query, $search)
    {
        return $query->whereHas('bookCopy.book', function ($q) use ($search) {
            $q->where('title', 'LIKE', "%{$search}%");
        });
    }
}
