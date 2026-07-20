<?php

namespace App\Services\Academics;

use App\Models\Academics\AcademicDocument;
use App\Models\Academics\Book;
use App\Models\Academics\Chapter;
use App\Models\Academics\Conference;
use App\Models\Academics\DaySetting;
use App\Models\Academics\Domain;
use App\Models\Academics\DomainModule;
use App\Models\Academics\ExamGroup;
use App\Models\Academics\ExamResult;
use App\Models\Academics\ExamSchedule;
use App\Models\Academics\GoogleMeet;
use App\Models\Academics\Grooming;
use App\Models\Academics\GroomingDomain;
use App\Models\Academics\GroomingParameter;
use App\Models\Academics\Homework;
use App\Models\Academics\Lesson;
use App\Models\Academics\OnlineExam;
use App\Models\Academics\Question;
use App\Models\Academics\Subject;
use App\Models\Academics\SubjectGroup;
use App\Models\Academics\Syllabus;
use App\Models\Academics\TermSetting;
use App\Models\Academics\TestGroup;
use App\Models\Academics\TestResult;
use App\Models\Academics\TestSchedule;
use App\Models\Academics\Timetable;
use App\Models\Academics\Topic;
use App\Models\Academics\WeekSetting;
use App\Models\LibraryMember;
use App\Models\Staff;

class AcademicModuleRegistry
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public function all(): array
    {
        return [
            'books' => $this->module('Books', Book::class, 'books', 'admin.academics.books.index', 'book', ['book_title', 'book_no', 'isbn_no', 'subject', 'author'], ['book_title', 'book_no', 'subject', 'author', 'qty', 'available']),
            'chapters' => $this->module('Chapters', Chapter::class, 'chapter', 'admin.academics.chapters.index', 'chapter', ['name', 'urdu', 'note'], ['name', 'class_id', 'subject_id', 'is_active']),
            'conferences' => $this->module('Conferences', Conference::class, 'conferences', 'admin.academics.conferences.index', 'conference', ['title', 'subject', 'description'], ['title', 'date', 'duration', 'subject', 'status']),
            'day-settings' => $this->module('Day Settings', DaySetting::class, 'day', 'admin.academics.day-settings.index', 'daysetting', ['name', 'note'], ['name', 'date', 'term_id', 'week_id', 'is_active']),
            'documents' => $this->module('Academic Documents', AcademicDocument::class, 'upload_contents', 'admin.academics.documents.index', 'documentsaca', ['real_name', 'img_name', 'vid_title'], ['real_name', 'file_type', 'file_size', 'created_at']),
            'domains' => $this->module('Domains', Domain::class, 'domain', 'admin.academics.domains.index', 'domain', ['name'], ['name', 'subject_id', 'is_active']),
            'domain-modules' => $this->module('Domain Modules', DomainModule::class, 'domainmodules', 'admin.academics.domain-modules.index', 'domainmodules', ['name', 'urdu', 'note'], ['name', 'urdu', 'is_active']),
            'exam-groups' => $this->module('Exam Groups', ExamGroup::class, 'exam_groups', 'admin.academics.exam-groups.index', 'examgroup', ['name', 'exam_type', 'description'], ['name', 'exam_type', 'is_active']),
            'exam-results' => $this->module('Exam Results', ExamResult::class, 'exam_group_exam_results', 'admin.academics.exam-results.index', 'examresult', ['attendence', 'note'], ['attendence', 'get_marks', 'is_active']),
            'exam-schedules' => $this->module('Exam Schedules', ExamSchedule::class, 'exam_group_class_batch_exam_subjects', 'admin.academics.exam-schedules.index', 'examschedule', ['room_no', 'syllabus'], ['date_from', 'time_from', 'duration', 'room_no', 'max_marks']),
            'google-meet' => $this->module('Google Meet', GoogleMeet::class, 'gmeet', 'admin.academics.google-meet.index', 'gmeet', ['title', 'subject', 'url', 'description'], ['title', 'date', 'duration', 'subject', 'status']),
            'grooming' => $this->module('Grooming', Grooming::class, 'grooming_analysis_results', 'admin.academics.grooming.index', 'grooming', ['note'], ['date', 'student_id', 'domain_id', 'parameter_id', 'get_marks']),
            'grooming-domains' => $this->module('Grooming Domains', GroomingDomain::class, 'groomingdomain', 'admin.academics.grooming-domains.index', 'groomingdomain', ['name', 'note'], ['name', 'total_marks', 'is_active']),
            'grooming-parameters' => $this->module('Grooming Parameters', GroomingParameter::class, 'groomingparameter', 'admin.academics.grooming-parameters.index', 'groomingparameter', ['name', 'note'], ['name', 'domain_id', 'total_marks', 'is_active']),
            'homework' => $this->module('Homework', Homework::class, 'homework', 'admin.academics.homework.index', 'homework', ['description', 'document'], ['homework_date', 'submit_date', 'class_id', 'section_id', 'staff_id']),
            'lessons' => $this->module('Lessons', Lesson::class, 'lesson', 'admin.academics.lessons.index', 'lesson', ['daily_diary', 'home_work', 'lesson_plan', 'vid_title'], ['class_id', 'subject_id', 'chapter_id', 'topic_id', 'status']),
            'members' => $this->module('Members', LibraryMember::class, 'libarary_members', 'admin.academics.members.index', 'member', ['library_card_no', 'member_type'], ['library_card_no', 'member_type', 'member_id', 'is_active']),
            'online-exams' => $this->module('Online Exams', OnlineExam::class, 'onlineexam', 'admin.academics.online-exams.index', 'onlineexam', ['exam', 'description'], ['exam', 'attempt', 'exam_from', 'exam_to', 'is_active']),
            'paper-generate' => $this->module('Paper Generate', Question::class, 'questions', 'admin.academics.paper-generate.index', 'papergenerate', ['questioneng', 'questionurdu', 'question_type'], ['question_type', 'level', 'class_id', 'subject_id', 'chapter_id']),
            'questions' => $this->module('Questions', Question::class, 'questions', 'admin.academics.questions.index', 'question', ['questioneng', 'questionurdu', 'question_type'], ['question_type', 'level', 'class_id', 'subject_id', 'chapter_id']),
            'subjects' => $this->module('Subjects', Subject::class, 'subjects', 'admin.academics.subjects.index', 'subject', ['name', 'code', 'type'], ['name', 'code', 'type', 'is_active']),
            'subject-groups' => $this->module('Subject Groups', SubjectGroup::class, 'subject_groups', 'admin.academics.subject-groups.index', 'subjectgroup', ['name', 'description'], ['name', 'class_id', 'created_at']),
            'syllabus' => $this->module('Syllabus', Syllabus::class, 'syllabus', 'admin.academics.syllabus.index', 'syllabus', ['presentation', 'attachment', 'vid_title'], ['class_id', 'section_id', 'subject_id', 'term_id', 'status']),
            'teachers' => $this->module('Teachers', Staff::class, 'staff', 'admin.academics.teachers.index', 'teacher', ['name', 'surname', 'employee_id', 'email'], ['employee_id', 'name', 'email', 'role_id', 'is_active']),
            'term-settings' => $this->module('Term Settings', TermSetting::class, 'term', 'admin.academics.term-settings.index', 'termsetting', ['name', 'note'], ['name', 'is_active']),
            'test-groups' => $this->module('Test Groups', TestGroup::class, 'test_groups', 'admin.academics.test-groups.index', 'testgroup', ['name', 'test_type', 'description'], ['name', 'test_type', 'is_active']),
            'test-results' => $this->module('Test Results', TestResult::class, 'test_group_test_results', 'admin.academics.test-results.index', 'testresult', ['attendence', 'note'], ['attendence', 'get_marks', 'is_active']),
            'test-schedules' => $this->module('Test Schedules', TestSchedule::class, 'test_group_class_batch_test_subjects', 'admin.academics.test-schedules.index', 'testschedule', ['room_no', 'syllabus'], ['date_from', 'time_from', 'duration', 'room_no', 'max_marks']),
            'timetables' => $this->module('Timetables', Timetable::class, 'subject_timetable', 'admin.academics.timetables.index', 'timetable', ['day', 'room_no'], ['day', 'class_id', 'section_id', 'subject_id', 'staff_id', 'room_no']),
            'topics' => $this->module('Topics', Topic::class, 'topic', 'admin.academics.topics.index', 'topic', ['name', 'urdu', 'note'], ['name', 'class_id', 'subject_id', 'chapter_id', 'is_active']),
            'week-settings' => $this->module('Week Settings', WeekSetting::class, 'week', 'admin.academics.week-settings.index', 'weeksetting', ['name', 'note'], ['name', 'start_date', 'end_date', 'term_id', 'is_active']),
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
    private function module(string $label, string $model, string $table, string $route, string $permission, array $search, array $columns): array
    {
        return compact('label', 'model', 'table', 'route', 'permission', 'search', 'columns');
    }
}
