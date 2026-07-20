<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enquiry extends Model
{
    protected $table = 'enquiry';

    protected $fillable = [
        'brc_id',
        'enquiry_no',
        'name',
        'contact',
        'country_code',
        'email',
        'father_name',
        'address',
        'phone',
        'whatsapp',
        'reference',
        'date',
        'description',
        'follow_up_date',
        'note',
        'source',
        'status',
        'created_by',
        'updated_by',
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
            'date' => 'date',
            'follow_up_date' => 'date',
            'created_by' => 'integer',
            'updated_by' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
