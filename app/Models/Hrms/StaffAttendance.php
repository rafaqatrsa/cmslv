<?php

namespace App\Models\Hrms;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffAttendance extends HrmsModel
{
    protected $table = 'staff_attendance';

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'date_time_in' => 'datetime',
            'date_time_out' => 'datetime',
            'in_time' => 'datetime:H:i:s',
            'out_time' => 'datetime:H:i:s',
            'created_at' => 'datetime',
            'updated_at' => 'date',
        ];
    }
}
