<?php

namespace App\Models\Adm;

class Achievement extends AdmModel
{
    protected $table = 'student_timeline';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'timeline_date' => 'date',
            'date' => 'date',
        ];
    }
}
