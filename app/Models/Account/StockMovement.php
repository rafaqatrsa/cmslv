<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends AccountModel
{
    protected $table = 'product_stock_history';

    const UPDATED_AT = null;

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    protected function casts(): array
    {
        return [
            'quantity_added' => 'decimal:2',
            'stock_date' => 'date',
            'created_at' => 'datetime',
        ];
    }
}
