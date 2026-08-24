<?php

namespace App\Http\Requests\Admin\Adm;

use App\Models\Adm\Student;
use App\Models\Branch;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentAdmissionRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $aliases = [
            'pervious_school_id' => 'previous_school_id',
            'class_left' => 'pervious_class',
            'leaving_date' => 'school_leaving_date',
        ];
        $normalized = [];
        foreach ($aliases as $from => $to) {
            if ($this->filled($from) && ! $this->filled($to)) {
                $normalized[$to] = $this->input($from);
            }
        }
        if ($normalized !== []) {
            $this->merge($normalized);
        }
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $studentId = $this->route('student');
        $studentId = is_object($studentId) ? $studentId->getKey() : $studentId;

        return [
            'brc_id' => ['nullable', 'integer', Rule::exists((new Branch)->getTable(), 'id')],
            'regd_id' => ['nullable', 'integer'],
            'admission_no' => ['required', 'string', 'max:100', Rule::unique((new Student)->getTable(), 'admission_no')->ignore($studentId)],
            'staff_id' => ['nullable', 'integer'],
            'session_id' => ['required', 'integer', Rule::exists('sessions', 'id')],
            'adcademicyear_id' => ['required', 'integer', Rule::exists('adcademicyear', 'id')],
            'class_id' => ['required', 'integer', Rule::exists('classes', 'id')],
            'section_id' => ['required', 'integer', Rule::exists('sections', 'id')],
            'firstname' => ['required', 'string', 'max:100'],
            'middlename' => ['nullable', 'string', 'max:100'],
            'lastname' => ['nullable', 'string', 'max:100'],
            'dob' => ['required', 'date'],
            'gender' => ['required', 'string', 'max:100'],
            'admission_date' => ['required', 'date'],
            'district_id' => ['nullable', 'integer'],
            'tehsils_id' => ['nullable', 'integer'],
            'area_id' => ['nullable', 'integer'],
            'country_id' => ['nullable', 'integer'],
            'province_id' => ['nullable', 'integer'],
            'division_id' => ['nullable', 'integer'],
            'religion_id' => ['nullable', 'integer'],
            'medium_id' => ['nullable', 'integer'],
            'previous_school_id' => ['nullable', 'integer'],
            'pervious_class' => ['nullable', 'string', 'max:255'],
            'school_leaving_date' => ['nullable', 'date'],
            'current_address' => ['nullable', 'string'],
            'permanent_address' => ['nullable', 'string'],
            'email' => ['nullable', 'email', 'max:100'],
            'mobileno' => ['nullable', 'string', 'max:100'],
            'mobile_country_code' => ['nullable', 'string', 'max:100'],
            'category_id' => ['nullable'],
            'cast_id' => ['nullable', 'integer'],
            'skill_id' => ['nullable', 'integer'],
            'guardian_is' => ['required', 'string', 'max:100'],
            'father_name' => ['required', 'string', 'max:100'],
            'father_phone' => ['required', 'string', 'max:100'],
            'father_cnic' => ['required', 'string', 'max:255'],
            'guardian_name' => ['required', 'string', 'max:100'],
            'guardian_phone' => ['required', 'string', 'max:100'],
            'guardian_relation' => ['nullable', 'string', 'max:100'],
            'guardian_address' => ['nullable', 'string'],
            'guardian_occupation' => ['nullable', 'string', 'max:150'],
            'guardian_email' => ['nullable', 'email', 'max:100'],
            'father_country_code' => ['nullable', 'string', 'max:100'],
            'father_occupation' => ['nullable', 'string', 'max:100'],
            'father_education_id' => ['nullable', 'integer'],
            'father_living_id' => ['nullable', 'integer'],
            'mother_name' => ['nullable', 'string', 'max:100'],
            'mother_country_code' => ['nullable', 'string', 'max:100'],
            'mother_phone' => ['nullable', 'string', 'max:100'],
            'mother_cnic' => ['nullable', 'string', 'max:100'],
            'mother_occupation' => ['nullable', 'string', 'max:100'],
            'mother_education_id' => ['nullable', 'integer'],
            'mother_living_id' => ['nullable', 'integer'],
            'height' => ['nullable', 'string', 'max:100'],
            'weight' => ['nullable', 'string', 'max:100'],
            'blood_group' => ['nullable', 'string', 'max:200'],
            'other_phone' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:200'],
            'bank_id' => ['nullable', 'integer'],
            'bank_account_title' => ['nullable', 'string', 'max:255'],
            'bank_account_no' => ['nullable', 'string', 'max:100'],
            'concession_reason_type_id' => ['nullable', 'integer'],
            'concession_remark' => ['nullable', 'string'],
            'issue_date' => ['required', 'date'],
            'due_date' => ['required', 'date'],
            'receiving_date' => ['required', 'date'],
            'file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
            'father_pic' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
            'mother_pic' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
            'guardian_pic' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
            'fee_mode' => ['nullable', 'in:monthly,installments'],
            'fee_rows' => ['nullable', 'array'],
            'fee_rows.*.feetype_id' => ['nullable', 'integer', Rule::exists('accountshead', 'id')],
            'fee_rows.*.fee_amount' => ['nullable', 'numeric', 'min:0'],
            'fee_rows.*.current_amount' => ['nullable', 'numeric', 'min:0'],
            'fee_rows.*.frequency' => ['nullable', 'string', 'max:255'],
            'fee_rows.*.note' => ['nullable', 'string'],
            'document_title' => ['nullable', 'string', 'max:200'],
            'document' => ['nullable', 'file', 'max:10240'],
        ];
    }
}
