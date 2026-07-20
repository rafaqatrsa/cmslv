<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'system_settings';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'brc_id' => 'integer',
            'session_id' => 'integer',
            'regd_date' => 'date',
            'maintenance_mode' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'date',
        ];
    }
}
