<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    public const UPDATED_AT = null;

    protected $table = 'front_cms_programs';

    protected $fillable = [
        'brc_id',
        'type',
        'slug',
        'url',
        'title',
        'date',
        'event_start',
        'event_end',
        'event_venue',
        'description',
        'is_active',
        'meta_title',
        'meta_description',
        'meta_keyword',
        'feature_image',
        'image',
        'publish_date',
        'publish',
        'sidebar',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'brc_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', 'yes');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('publish', '1');
    }

    public function scopeForBranch(Builder $query, Branch $branch): Builder
    {
        return $query->where('brc_id', $branch->id);
    }

    public function scopeBySlug(Builder $query, string $slug): Builder
    {
        return $query->where('slug', $slug)->orWhere('url', $slug);
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
