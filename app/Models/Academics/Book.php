<?php

namespace App\Models\Academics;

class Book extends AcademicModel
{
    protected $table = 'books';

    protected function casts(): array
    {
        return [
            'qty' => 'integer',
            'perunitcost' => 'decimal:2',
            'postdate' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'date',
        ];
    }
}
