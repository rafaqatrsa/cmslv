<?php

namespace App\Models\Adm;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends AdmModel
{
    protected $table = 'students';

    protected function casts(): array
    {
        return [
            'admission_date' => 'date',
            'dob' => 'date',
            'school_leaving_date' => 'date',
            'measurement_date' => 'date',
            'disable_at' => 'date',
            'enable_at' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'date',
        ];
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(StudentSession::class, 'student_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(StudentDocument::class, 'student_id');
    }
}
