<?php

namespace App\Models\Hrms;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class HrmsModel extends Model
{
    protected $guarded = ['id'];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where(function (Builder $query): void {
            $query->where('is_active', 1)->orWhere('is_active', 'yes')->orWhere('is_active', '1');
        });
    }
}
