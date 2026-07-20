<?php

namespace App\Services\User;

use App\Models\Adm\Student;
use App\Models\Adm\StudentSession;
use App\Models\User;
use App\Services\AcademicSessionContext;
use App\Services\BranchContext;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class UserContext
{
    public function __construct(
        private readonly BranchContext $branchContext,
        private readonly AcademicSessionContext $sessionContext,
    ) {}

    public function user(): User
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            throw new RuntimeException('Authenticated user is required.');
        }

        return $user;
    }

    public function isStudent(): bool
    {
        $user = $this->user();
        $role = mb_strtolower((string) data_get($user, 'role'));
        $username = mb_strtolower((string) data_get($user, 'username'));

        return $role === 'student'
            || str_starts_with($username, 'std')
            || (data_get($user, 'user_id') !== null && data_get($user, 'sibling_id') === null);
    }

    public function isParent(): bool
    {
        $user = $this->user();
        $role = mb_strtolower((string) data_get($user, 'role'));
        $username = mb_strtolower((string) data_get($user, 'username'));

        return $role === 'parent'
            || str_starts_with($username, 'parent')
            || data_get($user, 'sibling_id') !== null
            || filled(data_get($user, 'childs'));
    }

    public function student(): ?Student
    {
        if (! Schema::hasTable('students')) {
            return null;
        }

        $studentId = data_get($this->user(), 'user_id');

        if (! is_numeric($studentId)) {
            return null;
        }

        return Student::query()->find((int) $studentId);
    }

    public function selectedStudent(?int $requestedStudentId = null): ?Student
    {
        $students = $this->accessibleStudents();

        if ($students->isEmpty()) {
            return null;
        }

        if ($requestedStudentId !== null) {
            return $students->firstWhere('id', $requestedStudentId);
        }

        return $students->first();
    }

    public function studentSession(?int $studentId = null): ?StudentSession
    {
        if (! Schema::hasTable('student_session')) {
            return null;
        }

        $student = $studentId !== null
            ? $this->selectedStudent($studentId)
            : $this->selectedStudent();

        if (! $student) {
            return null;
        }

        return StudentSession::query()
            ->where('student_id', $student->id)
            ->when($this->branchId(), fn ($query) => $query->where('brc_id', $this->branchId()))
            ->when($this->academicSessionId(), fn ($query) => $query->where('session_id', $this->academicSessionId()))
            ->latest('id')
            ->first();
    }

    public function siblingId(): ?int
    {
        $siblingId = data_get($this->user(), 'sibling_id');

        return is_numeric($siblingId) ? (int) $siblingId : null;
    }

    public function branchId(): ?int
    {
        $branchId = data_get($this->user(), 'brc_id') ?: $this->branchContext->id();

        return is_numeric($branchId) ? (int) $branchId : null;
    }

    public function academicSessionId(): ?int
    {
        return $this->sessionContext->id();
    }

    /**
     * @return EloquentCollection<int, Student>
     */
    public function accessibleStudents(): EloquentCollection
    {
        if (! Schema::hasTable('students')) {
            return new EloquentCollection;
        }

        if ($this->isStudent()) {
            $student = $this->student();

            return $student ? new EloquentCollection([$student]) : new EloquentCollection;
        }

        $studentIds = $this->childIds();
        $siblingId = $this->siblingId();

        if ($studentIds->isEmpty() && $siblingId === null) {
            return new EloquentCollection;
        }

        return Student::query()
            ->where(function ($query) use ($studentIds, $siblingId): void {
                $query->when($studentIds->isNotEmpty(), fn ($query) => $query->whereIn('id', $studentIds))
                    ->when($siblingId !== null, fn ($query) => $query->orWhere('student_sibling_id', $siblingId));
            })
            ->when($this->branchId(), fn ($query) => $query->where('brc_id', $this->branchId()))
            ->latest('id')
            ->get();
    }

    public function canAccessStudent(int $studentId): bool
    {
        return $this->accessibleStudents()->contains('id', $studentId);
    }

    private function childIds(): Collection
    {
        $childs = data_get($this->user(), 'childs');

        if (! is_string($childs) || trim($childs) === '') {
            return collect();
        }

        return collect(preg_split('/[,\s|]+/', $childs) ?: [])
            ->filter(fn (string $value) => is_numeric($value))
            ->map(fn (string $value) => (int) $value)
            ->values();
    }
}
