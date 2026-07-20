<?php

namespace App\Models\Adm;

class StaffAttendance extends AdmModel
{
    protected $table = 'staff_attendance';

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
