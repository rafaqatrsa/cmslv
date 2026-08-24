<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemNotification extends Model
{
    public const UPDATED_AT = null;

    protected $table = 'system_notification';

    protected $guarded = ['id'];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'brc_id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', 'yes')->orWhere('is_active', '1');
    }

    public function scopeForBranch(Builder $query, int $branchId): Builder
    {
        return $query->where('brc_id', $branchId);
    }

    protected function casts(): array
    {
        return [
            'brc_id' => 'integer',
            'session_id' => 'integer',
            'role_id' => 'integer',
            'receiver_id' => 'integer',
            'receiver_other_id' => 'integer',
            'date' => 'datetime',
            'created_at' => 'datetime',
        ];
    }
}
