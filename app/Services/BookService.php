<?php

namespace App\Services;

use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Support\Facades\Cache;
use Spatie\QueryBuilder\QueryBuilder;

class BookService
{
    public function list()
    {
        return Cache::tags(['books'])->remember('all_books', 3600, function () {
            return new BookResource(
                QueryBuilder::for(Book::class)
                    ->with('copies')
                    ->allowedFilters('title', 'id', 'author')
                    ->latest()
                    ->paginate(request()->per_page ?? 10)
            );
        });
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
        Cache::tags(['books'])->flush();

        return $book->delete();
    }
}
