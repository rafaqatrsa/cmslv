<?php

namespace App\Models\Academics;

class GoogleMeet extends AcademicModel
{
    public const UPDATED_AT = null;

    protected $table = 'gmeet';

    protected $hidden = ['api_data'];
}
