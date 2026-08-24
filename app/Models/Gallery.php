<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gallery extends Model
{
    public const UPDATED_AT = null;

    protected $table = 'front_cms_media_gallery';

    protected $fillable = [
        'brc_id',
        'image',
        'thumb_path',
        'dir_path',
        'img_name',
        'thumb_name',
        'file_type',
        'file_size',
        'vid_url',
        'vid_title',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'brc_id');
    }

    public function scopeForBranch(Builder $query, Branch $branch): Builder
    {
        return $query->where('brc_id', $branch->id);
    }

    protected function casts(): array
    {
        return [
            'brc_id' => 'integer',
            'created_at' => 'datetime',
        ];
    }
}
