<?php

namespace App\Services\Teacher;

use App\Models\Staff;
use App\Services\AcademicSessionContext;
use App\Services\BranchContext;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class TeacherContext
{
    public function __construct(
        private readonly BranchContext $branchContext,
        private readonly AcademicSessionContext $sessionContext,
    ) {}

    public function teacher(): ?Staff
    {
        if (! Schema::hasTable((new Staff)->getTable())) {
            return null;
        }

        return Staff::query()->where('user_id', Auth::id())->active()->first();
    }

    public function staffId(): ?int
    {
        return $this->teacher()?->id;
    }

    public function branchId(): ?int
    {
        return $this->teacher()?->brc_id ?: $this->branchContext->id();
    }

    public function academicSessionId(): ?int
    {
        return $this->sessionContext->id();
    }
}
