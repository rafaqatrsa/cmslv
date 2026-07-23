<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Branch extends Model
{
    protected $table = 'branch';

    protected $fillable = [
        'name',
        'regd_date',
        'is_royalty_type',
        'is_royalty_amount',
        'websiteurl',
        'country_id',
        'province_id',
        'division_id',
        'district_id',
        'tehsils_id',
        'area_id',
        'is_parent',
        'is_active',
    ];

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'brc_id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'brc_id');
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class, 'brc_id');
    }

    public function setting(): HasOne
    {
        return $this->hasOne(Setting::class, 'brc_id');
    }

    public function frontCmsSetting(): HasOne
    {
        return $this->hasOne(FrontCmsSetting::class, 'brc_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where(function (Builder $query): void {
            $query->where('is_active', 1)
                ->orWhere('is_active', '1')
                ->orWhere('is_active', 'yes')
                ->orWhere('is_active', 'active');
        });
    }

    public function scopeMatchingIdentifier(Builder $query, string $identifier): Builder
    {
        return $query->where(function (Builder $query) use ($identifier): void {
            $query->where('websiteurl', $identifier)
                ->orWhere('name', $identifier);

            if (ctype_digit($identifier)) {
                $query->orWhere('id', (int) $identifier);
            }
        });
    }

    protected function casts(): array
    {
        return [
            'regd_date' => 'date',
            'is_royalty_type' => 'integer',
            'is_royalty_amount' => 'integer',
            'country_id' => 'integer',
            'province_id' => 'integer',
            'division_id' => 'integer',
            'district_id' => 'integer',
            'tehsils_id' => 'integer',
            'area_id' => 'integer',
            'is_parent' => 'integer',
            'is_active' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
