<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RolePermission extends Model
{
    public const UPDATED_AT = null;

    protected $table = 'roles_permissions';

    protected $guarded = ['id'];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PermissionCategory::class, 'perm_cat_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'brc_id');
    }

    protected function casts(): array
    {
        return [
            'brc_id' => 'integer',
            'role_id' => 'integer',
            'perm_cat_id' => 'integer',
            'can_branch' => 'integer',
            'can_view' => 'integer',
            'can_add' => 'integer',
            'can_edit' => 'integer',
            'can_delete' => 'integer',
            'created_at' => 'datetime',
        ];
    }
}
