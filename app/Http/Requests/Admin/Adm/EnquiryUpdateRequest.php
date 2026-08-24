<?php

namespace App\Http\Requests\Admin\Adm;

use Illuminate\Foundation\Http\FormRequest;

class EnquiryUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'brc_id' => ['nullable', 'integer'],
            'name' => ['required', 'string', 'max:150'],
            'contact' => ['required', 'string', 'max:30'],
            'source' => ['required', 'string', 'max:100'],
            'date' => ['required', 'date'],
            'follow_up_date' => ['required', 'date'],
            'email' => ['nullable', 'email', 'max:150'],
            'class_id' => ['nullable', 'array'],
            'class_id.*' => ['nullable', 'integer'],
            'kid_name' => ['nullable', 'array'],
            'number_of_kids' => ['nullable', 'array'],
            'fee_policy' => ['nullable', 'array'],
        ];
    }
}
