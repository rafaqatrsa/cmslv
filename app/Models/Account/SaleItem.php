<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends AccountModel
{
    protected $table = 'sale_items';

    public $timestamps = false;

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    protected function casts(): array
    {
        return [
            'net_unit_price' => 'decimal:2',
            'unit_cost' => 'decimal:2',
            'quantity' => 'decimal:2',
            'item_tax' => 'decimal:2',
            'item_discount' => 'decimal:2',
            'real_unit_price' => 'decimal:2',
            'unit_quantity' => 'decimal:2',
        ];
    }
}
