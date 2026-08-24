<?php

namespace App\Http\Requests\Admin\Adm;

use App\Models\Adm\StudentSession;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class TransferClassSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'check' => ['required', 'array', 'min:1'],
            'check.*' => ['integer', 'distinct', 'exists:student_session,id'],
            'brc_transfer_id' => ['nullable', 'integer', 'exists:branch,id'],
            'session_id' => ['required', 'integer', 'exists:sessions,id'],
            'class_transfer_id' => ['required', 'integer', 'exists:classes,id'],
            'section_transfer_id' => [
                'required',
                'integer',
                Rule::exists('class_sections', 'section_id')
                    ->where('class_id', $this->integer('class_transfer_id'))
                    ->whereIn('is_active', ['yes', '1', 1]),
            ],
            'fee_transfer_mode' => ['required', Rule::in(['old_fees', 'next_class_fee'])],
            'source_brc_id' => ['nullable', 'integer', 'exists:branch,id'],
            'source_session_id' => ['nullable', 'integer', 'exists:sessions,id'],
            'source_class_id' => ['required', 'integer', 'exists:classes,id'],
            'source_section_id' => ['required', 'integer', 'exists:sections,id'],
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator): void {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $query = StudentSession::query()
                ->whereIn('id', $this->input('check', []))
                ->where('class_id', $this->integer('source_class_id'))
                ->where('section_id', $this->integer('source_section_id'));

            if ($this->filled('source_brc_id')) {
                $query->where('brc_id', $this->integer('source_brc_id'));
            }
            if ($this->filled('source_session_id')) {
                $query->where('session_id', $this->integer('source_session_id'));
            }

            if ($query->count() !== count($this->input('check', []))) {
                $validator->errors()->add('check', 'One or more selected students are no longer in the selected class and section.');
            }
        }];
    }
}
