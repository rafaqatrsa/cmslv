<?php

namespace App\Models\Account;

class Supplier extends AccountModel
{
    protected $table = 'supplier';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'disable_at' => 'date',
            'enable_at' => 'date',
            'is_active' => 'boolean',
        ];
    }
}
