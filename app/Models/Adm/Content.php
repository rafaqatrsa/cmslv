<?php

namespace App\Models\Adm;

class Content extends AdmModel
{
    protected $table = 'share_contents';

    const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'share_date' => 'date',
            'valid_upto' => 'date',
            'created_at' => 'datetime',
        ];
    }
}
