<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookCopy extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = ['book_id', 'status', 'repair_history'];

    protected $casts = [
        'repair_history' => 'array',
    ];

    public function book(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function scopeBook($query, $search)
    {
        return $query->whereHas('book', function ($q) use ($search) {
            $q->where('title', 'LIKE', "%{$search}%");
        });
    }
}
