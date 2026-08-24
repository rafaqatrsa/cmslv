<?php

namespace App\Models\Front;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends FrontModel
{
    protected $table = 'front_cms_menus';

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'menu_id');
    }

    protected function casts(): array
    {
        return [
            'brc_id' => 'integer',
            'open_new_tab' => 'boolean',
            'publish' => 'integer',
            'created_at' => 'datetime',
        ];
    }
}
