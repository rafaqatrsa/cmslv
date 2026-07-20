<?php

namespace App\Services\Teacher;

use App\Models\Academics\Conference;
use App\Models\Academics\ExamResult;
use App\Models\Academics\ExamSchedule;
use App\Models\Academics\GoogleMeet;
use App\Models\Academics\Grooming;
use App\Models\Academics\Homework;
use App\Models\Academics\Lesson;
use App\Models\Academics\Syllabus;
use App\Models\Academics\TermSetting;
use App\Models\Academics\TestResult;
use App\Models\Academics\TestSchedule;
use App\Models\Academics\Timetable;
use App\Models\Adm\LeaveRequest;
use App\Models\Adm\StudentAttendance;
use App\Models\Staff;

class TeacherModuleRegistry
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public function all(): array
    {
        return [
            'dashboard' => $this->module('Dashboard', Timetable::class, 'subject_timetable', 'teacher.dashboard', ['day', 'room_no'], ['day', 'class_id', 'section_id', 'subject_id', 'room_no']),
            'leave-approvals' => $this->module('Leave Approvals', LeaveRequest::class, 'student_applyleave', 'teacher.leave-approvals.index', ['reason'], ['student_session_id', 'from_date', 'to_date', 'status', 'approve_date']),
            'conferences' => $this->module('Conferences', Conference::class, 'conferences', 'teacher.conferences.index', ['title', 'subject', 'description'], ['title', 'date', 'duration', 'subject', 'status']),
            'exam-results' => $this->module('Exam Results', ExamResult::class, 'exam_group_exam_results', 'teacher.exam-results.index', ['attendence', 'note'], ['attendence', 'get_marks', 'is_active']),
            'exam-schedules' => $this->module('Exam Schedules', ExamSchedule::class, 'exam_group_class_batch_exam_subjects', 'teacher.exam-schedules.index', ['room_no', 'syllabus'], ['date_from', 'time_from', 'duration', 'room_no', 'max_marks']),
            'google-meet' => $this->module('Google Meet', GoogleMeet::class, 'gmeet', 'teacher.google-meet.index', ['title', 'subject', 'url', 'description'], ['title', 'date', 'duration', 'subject', 'status']),
            'grooming' => $this->module('Grooming', Grooming::class, 'grooming_analysis_results', 'teacher.grooming.index', ['note'], ['date', 'student_id', 'domain_id', 'parameter_id', 'get_marks']),
            'homework' => $this->module('Homework', Homework::class, 'homework', 'teacher.homework.index', ['description', 'document'], ['homework_date', 'submit_date', 'class_id', 'section_id', 'staff_id']),
            'lessons' => $this->module('Lessons', Lesson::class, 'lesson', 'teacher.lessons.index', ['daily_diary', 'home_work', 'lesson_plan'], ['class_id', 'subject_id', 'chapter_id', 'topic_id', 'status']),
            'student-attendance' => $this->module('Student Attendance', StudentAttendance::class, 'student_attendences', 'teacher.student-attendance.index', ['remark'], ['student_session_id', 'date', 'attendence_type_id', 'remark']),
            'syllabus' => $this->module('Syllabus', Syllabus::class, 'syllabus', 'teacher.syllabus.index', ['presentation', 'attachment', 'vid_title'], ['class_id', 'section_id', 'subject_id', 'term_id', 'status']),
            'term-settings' => $this->module('Term Settings', TermSetting::class, 'term', 'teacher.term-settings.index', ['name', 'note'], ['name', 'is_active']),
            'test-results' => $this->module('Test Results', TestResult::class, 'test_group_test_results', 'teacher.test-results.index', ['attendence', 'note'], ['attendence', 'get_marks', 'is_active']),
            'test-schedules' => $this->module('Test Schedules', TestSchedule::class, 'test_group_class_batch_test_subjects', 'teacher.test-schedules.index', ['room_no', 'syllabus'], ['date_from', 'time_from', 'duration', 'room_no', 'max_marks']),
            'timetable' => $this->module('Timetable', Timetable::class, 'subject_timetable', 'teacher.timetable.index', ['day', 'room_no'], ['day', 'class_id', 'section_id', 'subject_id', 'room_no']),
            'profile' => $this->module('Profile', Staff::class, 'staff', 'teacher.profile.show', ['employee_id', 'name', 'surname', 'email'], ['employee_id', 'name', 'surname', 'email', 'is_active']),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function get(string $key): array
    {
        return $this->all()[$key];
    }

    /**
     * @param  class-string  $model
     * @param  array<int, string>  $search
     * @param  array<int, string>  $columns
     * @return array<string, mixed>
     */
    private function module(string $label, string $model, string $table, string $route, array $search, array $columns): array
    {
        return compact('label', 'model', 'table', 'route', 'search', 'columns');
    }
}
