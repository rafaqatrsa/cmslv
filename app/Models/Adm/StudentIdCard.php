<?php

namespace App\Models\Adm;

class StudentIdCard extends AdmModel
{
    protected $table = 'students_id_card';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }
}
