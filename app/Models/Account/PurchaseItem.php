<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseItem extends AccountModel
{
    protected $table = 'purchase_items';

    public $timestamps = false;

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'expiry' => 'date',
            'net_unit_cost' => 'decimal:2',
            'quantity' => 'decimal:2',
            'item_tax' => 'decimal:2',
            'item_discount' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'quantity_balance' => 'decimal:2',
            'unit_cost' => 'decimal:2',
            'real_unit_cost' => 'decimal:2',
            'quantity_received' => 'decimal:2',
            'unit_quantity' => 'decimal:2',
        ];
    }
}
