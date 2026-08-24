<?php

namespace App\Http\Requests\Admin\Adm;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentPromotionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $mode = $this->input('fee_promotion_mode');

        return [
            'brc_id_post' => ['required', 'integer', 'exists:branch,id'],
            'check' => ['required', 'array', 'min:1'],
            'check.*' => ['integer', 'distinct', 'exists:students,id'],
            'session_id' => ['required', 'integer', 'exists:sessions,id'],
            'class_promote_id' => ['required', 'integer', 'exists:classes,id'],
            'section_promote_id' => ['nullable', 'integer', Rule::exists('class_sections', 'section_id')->where('class_id', $this->integer('class_promote_id'))->whereIn('is_active', ['yes', '1', 1])],
            'fee_promotion_mode' => ['required', Rule::in(['previous_discount', 'full_fees', 'increment_previous_tuition_fee_amount', 'increment_previous_tuition_fee_percentage'])],
            'promotion_increment_amount' => [$mode === 'increment_previous_tuition_fee_amount' ? 'required' : 'nullable', 'numeric', 'min:0'],
            'promotion_increment_percentage' => [$mode === 'increment_previous_tuition_fee_percentage' ? 'required' : 'nullable', 'numeric', 'min:0'],
            'class_post' => ['required', 'integer', 'exists:classes,id'],
            'section_post' => ['nullable', 'integer', 'exists:sections,id'],
            'source_session_id' => ['nullable', 'integer', 'exists:sessions,id'],
            'result' => ['nullable', 'array'],
            'next_working' => ['nullable', 'array'],
        ];
    }
}
