<?php

namespace App\Models\Adm;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAttendance extends AdmModel
{
    protected $table = 'student_attendences';

    public function studentSession(): BelongsTo
    {
        return $this->belongsTo(StudentSession::class, 'student_session_id');
    }

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'date_time_in' => 'datetime',
            'date_time_out' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'date',
        ];
    }
}
