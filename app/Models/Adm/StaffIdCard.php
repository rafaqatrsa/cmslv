<?php

namespace App\Models\Adm;

class StaffIdCard extends AdmModel
{
    protected $table = 'staff_id_card';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }
}
