<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceBookSetItem extends AccountModel
{
    protected $table = 'sale_bookset_bill_detail';

    const UPDATED_AT = null;

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(InvoiceBookSet::class, 'sale_bill_id');
    }

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'purchase_price' => 'decimal:2',
            'amount' => 'decimal:2',
            'created_at' => 'datetime',
        ];
    }
}
