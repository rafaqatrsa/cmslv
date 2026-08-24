<?php

namespace App\Services\Hrms;

use Illuminate\Http\UploadedFile;

class HrDocumentService
{
    public function store(UploadedFile $file): string
    {
        return $file->store('hrms/documents', 'public');
    }
}
