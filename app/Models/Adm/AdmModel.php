<?php

namespace App\Models\Adm;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class AdmModel extends Model
{
    protected $guarded = ['id'];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where(function (Builder $query): void {
            $query->where('is_active', 'yes')
                ->orWhere('is_active', '1')
                ->orWhere('status', 1);
        });
    }
}
