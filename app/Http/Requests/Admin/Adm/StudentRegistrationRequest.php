<?php

namespace App\Http\Requests\Admin\Adm;

use App\Models\Branch;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentRegistrationRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $studentName = trim((string) $this->input('student_name'));

        if ($studentName !== '' && ! $this->filled('firstname')) {
            [$firstName, $lastName] = $this->splitStudentName($studentName);

            $this->merge([
                'firstname' => $firstName,
                'lastname' => $this->filled('lastname') ? $this->input('lastname') : $lastName,
            ]);
        }

        $mappings = [
            'b_form_no' => 'bayformno',
            'religion_id' => 'religion',
            'pervious_school_id' => 'previous_school_id',
            'class_left' => 'previous_class',
            'leaving_date' => 'pervious_schl_leaving_date',
        ];

        $normalized = [];

        foreach ($mappings as $from => $to) {
            if (! $this->filled($from) || $this->filled($to)) {
                continue;
            }

            $normalized[$to] = $this->input($from);
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
        return [
            'brc_id' => ['nullable', 'integer', Rule::exists((new Branch)->getTable(), 'id')],
            'regd_no' => ['required', 'string', 'max:100'],
            'class_id' => ['required', 'integer', Rule::exists('classes', 'id')],
            'session_id' => ['required', 'integer', Rule::exists('sessions', 'id')],
            'adcademicyear_id' => ['required', 'integer', Rule::exists('adcademicyear', 'id')],
            'regd_date' => ['required', 'date'],
            'firstname' => ['required', 'string', 'max:100'],
            'lastname' => ['nullable', 'string', 'max:100'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'student_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'mobile_country_code' => ['nullable', 'string', 'max:5'],
            'mobileno' => ['nullable', 'string', 'max:100'],
            'dob' => ['required', 'date'],
            'gender' => ['required', 'string', 'max:100'],
            'religion' => ['nullable', 'integer', Rule::exists('religion', 'id')],
            'medium_id' => ['nullable', 'integer', Rule::exists('medium', 'id')],
            'previous_school_id' => ['nullable', 'integer', Rule::exists('perviousschool', 'id')],
            'previous_class' => ['nullable', 'string', 'max:255'],
            'pervious_schl_leaving_date' => ['nullable', 'date'],
            'bayformno' => ['nullable', 'string', 'max:255'],
            'district_id' => ['nullable', 'integer', Rule::exists('district', 'id')],
            'tehsils_id' => ['nullable', 'integer', Rule::exists('tehsils', 'id')],
            'area_id' => ['nullable', 'integer', Rule::exists('area', 'id')],
            'father_name' => ['required', 'string', 'max:100'],
            'father_country_code' => ['nullable', 'string', 'max:5'],
            'father_phone' => ['required', 'string', 'max:100'],
            'father_occupation' => ['nullable', 'string', 'max:100'],
            'mother_name' => ['nullable', 'string', 'max:100'],
            'mother_country_code' => ['nullable', 'string', 'max:100'],
            'mother_phone' => ['nullable', 'string', 'max:100'],
            'mother_occupation' => ['nullable', 'string', 'max:100'],
            'father_cnic' => ['required', 'string', 'max:100'],
            'guardian_is' => ['required', 'string', 'in:father,mother,other'],
            'guardian_name' => ['required', 'string', 'max:255'],
            'guardian_relation' => ['required', 'string', 'max:255'],
            'guardian_phone' => ['required', 'string', 'max:255'],
            'guardian_country_code' => ['nullable', 'string', 'max:5'],
            'guardian_occupation' => ['nullable', 'string', 'max:255'],
            'guardian_email' => ['nullable', 'email', 'max:100'],
            'address' => ['nullable', 'string', 'max:100'],
            'issue_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date'],
            'regd_date_current' => ['nullable', 'date'],
            'is_active' => ['nullable', 'string', 'max:255'],
            'regd_status' => ['nullable', 'integer'],
            'username' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'max:255'],
            'registration_enquiry_id' => ['nullable', 'integer'],
            'registration_enquiry_kid_id' => ['nullable', 'integer'],
            'fee_rows' => ['nullable', 'array'],
            'fee_rows.*.feetype_id' => ['nullable', 'integer', Rule::exists('accountshead', 'id')],
            'fee_rows.*.frequency' => ['nullable', 'string', 'max:255'],
            'fee_rows.*.amount' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    /**
     * @return array{0: string, 1: ?string}
     */
    private function splitStudentName(string $studentName): array
    {
        $parts = preg_split('/\s+/', $studentName, 2) ?: [];
        $firstName = trim((string) ($parts[0] ?? ''));
        $lastName = trim((string) ($parts[1] ?? ''));

        return [$firstName, $lastName !== '' ? $lastName : null];
    }
}
