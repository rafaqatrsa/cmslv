<?php

namespace App\Models\Front;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class FrontModel extends Model
{
    protected $guarded = ['id'];

    public const UPDATED_AT = null;

    public function scopeActive(Builder $query): Builder
    {
        return $query->where(function (Builder $query): void {
            $query->where('is_active', 'yes')->orWhere('is_active', '1');
        });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where(function (Builder $query): void {
            $query->where('publish', 1)->orWhere('publish', '1');
        });
    }
}
