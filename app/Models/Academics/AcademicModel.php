<?php

namespace App\Models\Academics;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class AcademicModel extends Model
{
    protected $guarded = ['id'];

    public function scopeActive(Builder $query): Builder
    {
        $table = $query->getModel()->getTable();

        return $query->where(function (Builder $query) use ($table): void {
            $query->where($table.'.is_active', 1)
                ->orWhere($table.'.is_active', '1')
                ->orWhere($table.'.is_active', 'yes')
                ->orWhere($table.'.is_active', 'active');
        });
    }

    public function scopeForBranch(Builder $query, int $branchId): Builder
    {
        return $query->where('brc_id', $branchId);
    }

    public function scopeForSession(Builder $query, int $sessionId): Builder
    {
        return $query->where('session_id', $sessionId);
    }
}
