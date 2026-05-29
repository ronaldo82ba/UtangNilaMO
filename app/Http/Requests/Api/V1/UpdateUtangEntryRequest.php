<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUtangEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->route('borrower')->user_id === $this->user()->id;
    }

    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:1'],
            'description' => ['nullable', 'string', 'max:255'],
            'due_date' => ['nullable', 'date', 'after_or_equal:date'],
            'status' => ['nullable', Rule::in(['pending', 'partial', 'paid'])],
        ];
    }
}
