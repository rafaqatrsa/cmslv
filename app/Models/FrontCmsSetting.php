<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FrontCmsSetting extends Model
{
    public const UPDATED_AT = null;

    protected $table = 'front_cms_settings';

    protected $guarded = ['id'];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'brc_id');
    }

    protected function casts(): array
    {
        return [
            'brc_id' => 'integer',
            'is_active_rtl' => 'integer',
            'is_active_front_cms' => 'integer',
            'is_active_sidebar' => 'integer',
            'created_at' => 'datetime',
        ];
    }
}
