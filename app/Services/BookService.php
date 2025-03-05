<?php

namespace App\Services;

use App\Http\Resources\BookResource;
use App\Models\Book;
use Spatie\QueryBuilder\QueryBuilder;

class BookService
{
    public function list()
    {
        return new BookResource(
            QueryBuilder::for(Book::class)
                ->with('copies')
                ->allowedFilters('title', 'id', 'author')
                ->latest()
                ->paginate(request()->per_page ?? 10)
        );
    }

    public function newOrUpdate($request)
    {
        return Book::updateOrCreate(
            ['title' => $request->title],
            [
                'author' => $request->author,
                'genre' => $request->genre
            ]
        );
    }


    public function delete($book)
    {
        return $book->delete();
    }
}
