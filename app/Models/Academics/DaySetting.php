<?php

namespace App\Models\Academics;

class DaySetting extends AcademicModel
{
    protected $table = 'day';

    protected function casts(): array
    {
        return [
            'term_id' => 'integer',
            'week_id' => 'integer',
            'date' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
