<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Staff extends Authenticatable
{
    use Notifiable;

    protected $table = 'staff';

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'ch_password',
        'verification_code',
        'zoom_api_key',
        'zoom_api_secret',
    ];

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
        return $query->where('is_active', 1);
    }

    public function scopeForBranch(Builder $query, int $branchId): Builder
    {
        return $query->where('brc_id', $branchId);
    }

    protected function casts(): array
    {
        return [
            'brc_id' => 'integer',
            'category' => 'integer',
            'role_id' => 'integer',
            'department' => 'integer',
            'designation' => 'integer',
            'dob' => 'date',
            'date_of_joining' => 'date',
            'date_of_leaving' => 'date',
            'user_id' => 'integer',
            'is_active' => 'integer',
            'disable_at' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
