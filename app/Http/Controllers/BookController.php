<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Models\Book;
use App\Services\BookService;
use Illuminate\Http\Request;

class BookController extends Controller
{
    protected $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function index()
    {
        return $this->bookService->list();
    }

    public function newOrUpdate(BookRequest $request)
    {
        return $this->bookService->newOrUpdate($request);
    }


    public function destroy(Book $book)
    {
        return $this->bookService->delete($book);
    }
}
