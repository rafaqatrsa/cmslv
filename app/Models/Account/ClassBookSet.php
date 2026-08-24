<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassBookSet extends AccountModel
{
    protected $table = 'sale_bookset_bill';

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceBookSetItem::class, 'sale_bill_id');
    }

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'tax' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'balance_amount' => 'decimal:2',
        ];
    }
}
