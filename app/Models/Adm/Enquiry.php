<?php

namespace App\Models\Adm;

class Enquiry extends AdmModel
{
    protected $table = 'enquiry';

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'follow_up_date' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
