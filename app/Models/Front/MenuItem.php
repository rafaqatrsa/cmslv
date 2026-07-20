<?php

namespace App\Models\Front;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends FrontModel
{
    protected $table = 'front_cms_menu_items';

    public function menuRecord(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    protected function casts(): array
    {
        return [
            'open_new_tab' => 'boolean',
            'weight' => 'integer',
            'publish' => 'integer',
            'created_at' => 'datetime',
        ];
    }
}
