<?php

namespace App\Models\Hrms;

class HrManual extends HrmsModel
{
    protected $table = 'manual_supporthrm';

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'date',
        ];
    }
}
