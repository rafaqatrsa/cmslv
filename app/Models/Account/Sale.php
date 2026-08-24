<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends AccountModel
{
    protected $table = 'sales';

    const CREATED_AT = null;

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class, 'sale_id');
    }

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'due_date' => 'date',
            'grand_total' => 'decimal:2',
            'paid' => 'decimal:2',
            'return_sale_total' => 'decimal:2',
            'updated_at' => 'datetime',
        ];
    }
}
