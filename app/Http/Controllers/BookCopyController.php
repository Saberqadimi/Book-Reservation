<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookCopyRequest;
use App\Http\Requests\BookCopyUpdateRequest;
use App\Models\BookCopy;
use App\Services\BookCopyService;
use Illuminate\Http\Request;

class BookCopyController extends Controller
{
    private BookCopyService $bookCopyService;

    public function __construct(BookCopyService $bookCopyService)
    {
        $this->bookCopyService = $bookCopyService;
    }

    public function index()
    {
        return $this->bookCopyService->index();
    }

    public function store(BookCopyRequest $request)
    {
        return $this->bookCopyService->store($request);
    }

    public function update(BookCopyUpdateRequest $request , BookCopy $bookCopy)
    {
        return $this->bookCopyService->update($request , $bookCopy);
    }

    public function destroy(BookCopy $bookCopy)
    {
        return $this->bookCopyService->delete($bookCopy);
    }
}
