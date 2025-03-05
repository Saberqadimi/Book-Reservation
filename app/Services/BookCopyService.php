<?php

namespace App\Services;

use App\Http\Resources\BookCopyResource;
use App\Models\BookCopy;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class BookCopyService
{
    public function index()
    {
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
    }

    public function store($request)
    {
        return BookCopy::create($request->all());
    }

    public function update($request, BookCopy $bookCopy)
    {
        return $bookCopy->update($request->all());
    }

    public function delete(BookCopy $bookCopy)
    {
        return $bookCopy->delete();
    }
}
