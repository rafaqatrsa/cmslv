<?php

namespace App\Models\Adm;

use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentRegistration extends AdmModel
{
    protected $table = 'students_regd';

    protected function casts(): array
    {
        return [
            'regd_date' => 'date',
            'dob' => 'date',
            'pervious_schl_leaving_date' => 'date',
            'issue_date' => 'date',
            'due_date' => 'date',
            'regd_date_current' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function fees(): HasMany
    {
        return $this->hasMany(StudentRegistrationFee::class, 'student_regd_id');
    }
}
