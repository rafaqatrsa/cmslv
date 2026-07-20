<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseItem extends AccountModel
{
    protected $table = 'expenses_bill_items';

    public $timestamps = false;

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class, 'expenses_bill_id');
    }

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'debit_amount' => 'decimal:2',
            'credit_amount' => 'decimal:2',
        ];
    }
}
