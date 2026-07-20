<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Relations\HasMany;

class InvoiceBookSetReturn extends AccountModel
{
    protected $table = 'sale_bookset_return';

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceBookSetReturnItem::class, 'return_id');
    }

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'total_amount' => 'decimal:2',
            'payment_amount' => 'decimal:2',
            'balance_amount' => 'decimal:2',
        ];
    }
}
