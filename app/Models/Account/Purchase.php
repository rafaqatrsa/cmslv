<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends AccountModel
{
    protected $table = 'purchases';

    const CREATED_AT = null;

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class, 'purchase_id');
    }

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'due_date' => 'date',
            'total' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'paid' => 'decimal:2',
            'return_purchase_total' => 'decimal:2',
            'updated_at' => 'datetime',
        ];
    }
}
