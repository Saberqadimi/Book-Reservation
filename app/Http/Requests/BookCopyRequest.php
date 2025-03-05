<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookCopyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'book_id' => 'required|exists:books,id',
            'status' => 'required|in:available,reserved,damaged',
            'edition' => 'nullable|string|max:255',
            'published_year' => 'nullable|digits:4|integer|min:1800|max:' . date('Y'),
            'location' => 'nullable|string|max:255',
            'repair_history' => 'nullable|array',
            'repair_history.*.date' => 'required_with:repair_history|date',
            'repair_history.*.description' => 'required_with:repair_history|string|max:500'
        ];
    }

}
