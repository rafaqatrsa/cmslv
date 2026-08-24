<?php

namespace App\Models\Adm;

class Notification extends AdmModel
{
    protected $table = 'send_notification';

    protected function casts(): array
    {
        return [
            'publish_date' => 'date',
            'date' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'date',
        ];
    }
}
