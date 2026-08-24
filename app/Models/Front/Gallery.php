<?php

namespace App\Models\Front;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Gallery extends FrontModel
{
    protected $table = 'front_cms_media_gallery';

    public function programPhotos(): HasMany
    {
        return $this->hasMany(ProgramPhoto::class, 'media_gallery_id');
    }

    protected function casts(): array
    {
        return [
            'brc_id' => 'integer',
            'created_at' => 'datetime',
        ];
    }
}
