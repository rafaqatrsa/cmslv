<?php

namespace App\Http\Requests\Admin\Adm;

use App\Models\Adm\Sibling;
use App\Models\Branch;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SiblingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $sibling = $this->route('sibling');
        $siblingId = $sibling instanceof Sibling ? $sibling->getKey() : $sibling;
        $branchId = $this->integer('brc_id') ?: ($sibling instanceof Sibling ? $sibling->brc_id : null);

        return [
            'brc_id' => ['nullable', 'integer', Rule::exists((new Branch)->getTable(), 'id')],
            'sibling_code' => ['required', 'integer', Rule::unique((new Sibling)->getTable(), 'sibling_code')->where(fn ($query) => $query->where('brc_id', $branchId))->ignore($siblingId)],
            'sibling_name' => ['required', 'string', 'max:255'],
            'sibling_cnic' => ['required', 'string', 'max:255', Rule::unique((new Sibling)->getTable(), 'sibling_cnic')->where(fn ($query) => $query->where('brc_id', $branchId))->ignore($siblingId)],
            'sibling_phone' => ['required', 'string', 'max:255', Rule::unique((new Sibling)->getTable(), 'sibling_phone')->where(fn ($query) => $query->where('brc_id', $branchId))->ignore($siblingId)],
            'student_session_ids' => ['nullable', 'array'],
            'student_session_ids.*' => ['integer'],
            'remove_student_session_ids' => ['nullable', 'array'],
            'remove_student_session_ids.*' => ['integer'],
        ];
    }
}
