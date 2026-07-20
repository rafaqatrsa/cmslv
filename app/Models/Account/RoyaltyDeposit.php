<?php

namespace App\Models\Account;

class RoyaltyDeposit extends AccountModel
{
    protected $table = 'royalty_deposite_details';

    const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'due_date' => 'date',
            'date' => 'date',
            'royalty_month' => 'date',
            'royalty_month_date' => 'date',
            'amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'created_at' => 'datetime',
        ];
    }
}
