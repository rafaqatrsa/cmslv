<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends Model
{
    public const UPDATED_AT = null;

    protected $table = 'front_cms_pages';

    protected $fillable = [
        'brc_id',
        'page_type',
        'is_homepage',
        'title',
        'url',
        'type',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keyword',
        'name',
        'designation',
        'feature_image',
        'description',
        'photo',
        'sign',
        'publish_date',
        'publish',
        'sidebar',
        'is_active',
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
        return $query->where('publish', 1);
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
            'is_homepage' => 'boolean',
            'publish_date' => 'date',
            'publish' => 'integer',
            'sidebar' => 'integer',
            'created_at' => 'datetime',
        ];
    }
}
