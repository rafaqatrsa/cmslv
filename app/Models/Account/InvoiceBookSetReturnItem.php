<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceBookSetReturnItem extends AccountModel
{
    protected $table = 'sale_bookset_return_detail';

    const UPDATED_AT = null;

    public function return(): BelongsTo
    {
        return $this->belongsTo(InvoiceBookSetReturn::class, 'return_id');
    }

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'amount' => 'decimal:2',
            'created_at' => 'datetime',
        ];
    }
}
