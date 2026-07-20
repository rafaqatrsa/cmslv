<?php

namespace App\Models\Academics;

class AcademicDocument extends AcademicModel
{
    public const UPDATED_AT = null;

    protected $table = 'upload_contents';

    protected function casts(): array
    {
        return [
            'content_type_id' => 'integer',
            'upload_by' => 'integer',
            'created_at' => 'datetime',
        ];
    }
}
