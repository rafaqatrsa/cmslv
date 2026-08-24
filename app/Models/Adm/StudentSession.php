<?php

namespace App\Models\Adm;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentSession extends AdmModel
{
    protected $table = 'student_session';

    protected function casts(): array
    {
        return [
            'session_date' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function sibling(): BelongsTo
    {
        return $this->belongsTo(Sibling::class, 'student_sibling_id');
    }
}
