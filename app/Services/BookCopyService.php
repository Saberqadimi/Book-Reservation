<?php

namespace App\Services;

use App\Http\Resources\BookCopyResource;
use App\Models\BookCopy;
use Illuminate\Support\Facades\Cache;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class BookCopyService
{
    public function index()
    {
        return Cache::tags(['book_copies'])->remember('all_books', 3600, function () {
            return new BookCopyResource(
                QueryBuilder::for(BookCopy::class)
                    ->with('book')
                    ->allowedFilters(
                        AllowedFilter::exact('id'),
                        AllowedFilter::scope('book'),
                        AllowedFilter::exact('status'),
                    )
                    ->paginate(request()->per_page ?? 10)

            );
        });
    }

    public function store($request)
    {
        return BookCopy::create($request->all());
    }

    public function update($request, BookCopy $bookCopy)
    {
        Cache::tags(['books'])->flush();

        return $bookCopy->update($request->all());
    }

    public function delete(BookCopy $bookCopy)
    {
        Cache::tags(['books'])->flush();

        return $bookCopy->delete();
    }
}
