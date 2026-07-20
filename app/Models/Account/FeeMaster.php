<?php

namespace App\Models\Account;

class FeeMaster extends AccountModel
{
    protected $table = 'fee_groups_feetype';

    const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'created_at' => 'datetime',
        ];
    }
}
