<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookCopyRequest;
use App\Http\Requests\BookCopyUpdateRequest;
use App\Models\BookCopy;
use App\Services\BookCopyService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Gate;

class BookCopyController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

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
        if (!Gate::allows('store', BookCopy::class)) {
            abort(403, 'شما دسترسی به این بخش ندارید.');
        }
        return $this->bookCopyService->store($request);
    }

    public function update(BookCopyUpdateRequest $request, BookCopy $bookCopy)
    {
        if (!Gate::allows('update', BookCopy::class)) {
            abort(403, 'شما دسترسی به این بخش ندارید.');
        }
        return $this->bookCopyService->update($request, $bookCopy);
    }

    public function destroy(BookCopy $bookCopy)
    {
        if (!Gate::allows('delete', BookCopy::class)) {
            abort(403, 'شما دسترسی به این بخش ندارید.');
        }
        return $this->bookCopyService->delete($bookCopy);
    }
}
