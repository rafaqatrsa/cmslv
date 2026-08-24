<?php

namespace App\Services\Front;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FrontFileService
{
    public function store(UploadedFile $file, string $directory): string
    {
        return $file->store($directory, 'public');
    }

    public function exists(string $path): bool
    {
        return Storage::disk('public')->exists($path);
    }
}
