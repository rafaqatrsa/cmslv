<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $table = 'roles';

    protected $guarded = ['id'];

    public function permissions(): HasMany
    {
        return $this->hasMany(RolePermission::class, 'role_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', 1);
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'integer',
            'is_system' => 'integer',
            'is_superadmin' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'date',
        ];
    }
}
