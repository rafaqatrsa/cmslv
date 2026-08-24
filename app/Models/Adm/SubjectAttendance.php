<?php

namespace App\Models\Adm;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubjectAttendance extends AdmModel
{
    protected $table = 'student_subject_attendances';

    const UPDATED_AT = null;

    public function studentSession(): BelongsTo
    {
        return $this->belongsTo(StudentSession::class, 'student_session_id');
    }

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'created_at' => 'datetime',
        ];
    }
}
