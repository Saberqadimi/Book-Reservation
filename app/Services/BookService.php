<?php

namespace App\Services;

use App\Http\Resources\BookResource;
use App\Models\Book;
use Spatie\QueryBuilder\QueryBuilder;

class BookService
{
    private $bookResource;

    public function list()
    {
        $this->bookResource = new BookResource(
            QueryBuilder::for(Book::class)
                ->with('copies')
                ->latest()
                ->paginate(request()->per_page ?? 10)
        );
        return $this->bookResource;
    }

    public function newOrUpdate($request)
    {
        return Book::query()->updateOrCreate([$request->title], [$request->author, $request->genre]);
    }

    public function delete($book)
    {
        return $book->delete();
    }
}
