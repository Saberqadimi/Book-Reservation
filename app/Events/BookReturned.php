<?php

namespace App\Events;

use App\Models\BookCopy;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookReturned
{
    use Dispatchable, SerializesModels;

    public function __construct(BookCopy $bookCopy)
    {
        $this->bookCopy = $bookCopy;
    }
}
