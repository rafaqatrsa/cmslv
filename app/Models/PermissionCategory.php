<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PermissionCategory extends Model
{
    public const UPDATED_AT = null;

    protected $table = 'permission_category';

    protected $guarded = ['id'];

    public function rolePermissions(): HasMany
    {
        return $this->hasMany(RolePermission::class, 'perm_cat_id');
    }

    public function scopeForShortCode(Builder $query, string $shortCode): Builder
    {
        return $query->where('short_code', $shortCode);
    }

    protected function casts(): array
    {
        return [
            'perm_group_id' => 'integer',
            'enable_branch' => 'integer',
            'enable_view' => 'integer',
            'enable_add' => 'integer',
            'enable_edit' => 'integer',
            'enable_delete' => 'integer',
            'created_at' => 'datetime',
        ];
    }
}
