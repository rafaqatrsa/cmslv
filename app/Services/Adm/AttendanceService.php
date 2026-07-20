<?php

namespace App\Services\Adm;

use App\Models\Adm\StudentAttendance;
use App\Models\Adm\SubjectAttendance;
use Illuminate\Validation\ValidationException;

class AttendanceService
{
    public function assertDailyAttendanceIsUnique(int $studentSessionId, string $date): void
    {
        if (StudentAttendance::query()->where('student_session_id', $studentSessionId)->whereDate('date', $date)->exists()) {
            throw ValidationException::withMessages([
                'date' => 'Attendance already exists for this student and date.',
            ]);
        }
    }

    public function assertSubjectAttendanceIsUnique(int $studentSessionId, int $subjectTimetableId, string $date): void
    {
        if (SubjectAttendance::query()->where('student_session_id', $studentSessionId)->where('subject_timetable_id', $subjectTimetableId)->whereDate('date', $date)->exists()) {
            throw ValidationException::withMessages([
                'date' => 'Subject attendance already exists for this student, subject timetable, and date.',
            ]);
        }
    }
}
