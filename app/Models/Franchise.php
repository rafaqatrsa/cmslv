<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Franchise extends Model
{
    protected $table = 'branch';

    protected $fillable = [
        'name',
        'regd_date',
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

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', 1);
    }

    public function scopeFranchiseLocations(Builder $query): Builder
    {
        return $query->where('is_parent', 0);
    }

    protected function casts(): array
    {
        return [
            'regd_date' => 'date',
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
