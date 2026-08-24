<?php

namespace App\Models\Front;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends FrontModel
{
    protected $table = 'front_cms_programs';

    public function photos(): HasMany
    {
        return $this->hasMany(ProgramPhoto::class, 'program_id');
    }

    protected function casts(): array
    {
        return [
            'brc_id' => 'integer',
            'date' => 'date',
            'event_start' => 'date',
            'event_end' => 'date',
            'publish_date' => 'date',
            'sidebar' => 'integer',
            'created_at' => 'datetime',
        ];
    }
}
