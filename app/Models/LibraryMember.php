<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LibraryMember extends Model
{
    protected $table = 'libarary_members';

    protected $guarded = ['id'];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'brc_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', 'yes')->orWhere('is_active', '1');
    }

    protected function casts(): array
    {
        return [
            'brc_id' => 'integer',
            'member_id' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
