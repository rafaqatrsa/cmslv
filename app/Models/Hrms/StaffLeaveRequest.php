<?php

namespace App\Models\Hrms;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffLeaveRequest extends HrmsModel
{
    protected $table = 'staff_leave_request';

    public const UPDATED_AT = null;

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    protected function casts(): array
    {
        return [
            'leave_from' => 'date',
            'leave_to' => 'date',
            'date' => 'date',
            'created_at' => 'datetime',
        ];
    }
}
