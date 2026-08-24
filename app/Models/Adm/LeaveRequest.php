<?php

namespace App\Models\Adm;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends AdmModel
{
    protected $table = 'student_applyleave';

    const UPDATED_AT = null;

    public function studentSession(): BelongsTo
    {
        return $this->belongsTo(StudentSession::class, 'student_session_id');
    }

    protected function casts(): array
    {
        return [
            'from_date' => 'date',
            'to_date' => 'date',
            'apply_date' => 'date',
            'approve_date' => 'date',
            'created_at' => 'datetime',
        ];
    }
}
