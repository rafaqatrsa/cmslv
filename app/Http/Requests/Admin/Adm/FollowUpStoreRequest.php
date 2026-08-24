<?php

namespace App\Http\Requests\Admin\Adm;

use Illuminate\Foundation\Http\FormRequest;

class FollowUpStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'follow_up_date' => ['required', 'date'],
            'response' => ['required', 'string', 'max:5000'],
            'note' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
