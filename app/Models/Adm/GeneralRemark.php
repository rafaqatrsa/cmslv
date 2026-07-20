<?php

namespace App\Models\Adm;

class GeneralRemark extends AdmModel
{
    protected $table = 'general_remarks';

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
