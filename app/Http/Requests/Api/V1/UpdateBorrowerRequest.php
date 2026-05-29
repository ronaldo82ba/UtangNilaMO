<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBorrowerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->route('borrower')->user_id === $this->user()->id;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'nickname' => ['nullable', 'string', 'max:60'],
            'phone' => ['nullable', 'string', 'max:50'],
            'contact_number' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
