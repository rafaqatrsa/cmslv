<?php

namespace App\Models\Front;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramPhoto extends FrontModel
{
    protected $table = 'front_cms_program_photos';

    public function program(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'program_id');
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(Gallery::class, 'media_gallery_id');
    }
}
