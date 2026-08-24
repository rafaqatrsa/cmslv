<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Expense extends AccountModel
{
    protected $table = 'expenses_bill';

    public function items(): HasMany
    {
        return $this->hasMany(ExpenseItem::class, 'expenses_bill_id');
    }

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'grand_total' => 'decimal:2',
            'created_at' => 'datetime',
            'updated_at' => 'date',
        ];
    }
}
