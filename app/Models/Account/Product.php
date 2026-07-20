<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends AccountModel
{
    protected $table = 'products';

    public $timestamps = false;

    public function brandRecord(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ItemCategory::class, 'category_id');
    }

    protected function casts(): array
    {
        return [
            'cost' => 'decimal:2',
            'price' => 'decimal:2',
            'alert_quantity' => 'decimal:2',
            'quantity' => 'decimal:2',
            'promo_price' => 'decimal:4',
            'weight' => 'decimal:4',
            'track_quantity' => 'boolean',
            'promotion' => 'boolean',
            'featured' => 'boolean',
            'hide' => 'boolean',
            'hide_pos' => 'boolean',
            'start_date' => 'date',
            'end_date' => 'date',
            'initial_quantity_date' => 'date',
        ];
    }
}
