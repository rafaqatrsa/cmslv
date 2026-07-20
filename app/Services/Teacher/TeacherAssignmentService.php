<?php

namespace App\Services\Teacher;

use App\Models\Academics\Timetable;
use Illuminate\Support\Facades\Schema;

class TeacherAssignmentService
{
    public function __construct(
        private readonly TeacherContext $context,
    ) {}

    public function canAccessClass(int $classId): bool
    {
        return $this->exists(['class_id' => $classId]);
    }

    public function canAccessSection(int $classId, int $sectionId): bool
    {
        return $this->exists(['class_id' => $classId, 'section_id' => $sectionId]);
    }

    public function canAccessSubject(int $subjectId): bool
    {
        return $this->exists(['subject_id' => $subjectId]);
    }

    public function canAccessClassSectionSubject(int $classId, int $sectionId, int $subjectId): bool
    {
        return $this->exists([
            'class_id' => $classId,
            'section_id' => $sectionId,
            'subject_id' => $subjectId,
        ]);
    }

    /**
     * @param  array<string, int>  $filters
     */
    private function exists(array $filters): bool
    {
        if (! $this->context->staffId() || ! Schema::hasTable((new Timetable)->getTable())) {
            return false;
        }

        return Timetable::query()
            ->where('staff_id', $this->context->staffId())
            ->where($filters)
            ->exists();
    }
}
