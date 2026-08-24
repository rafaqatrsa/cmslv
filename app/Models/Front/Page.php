<?php

namespace App\Models\Front;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends FrontModel
{
    protected $table = 'front_cms_pages';

    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'page_id');
    }

    protected function casts(): array
    {
        return [
            'brc_id' => 'integer',
            'is_homepage' => 'boolean',
            'publish_date' => 'date',
            'publish' => 'integer',
            'sidebar' => 'integer',
            'created_at' => 'datetime',
        ];
    }
}
