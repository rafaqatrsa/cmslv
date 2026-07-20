# Academics CodeIgniter Migration Notes

## Migration Scope

The requested CodeIgniter Academics controllers, models, views, JavaScript, AJAX handlers, RBAC calls, and calculation routines were not present in this Laravel workspace. A workspace search found no `admin/academics` CodeIgniter source files and no `hasPrivilege(...)` usages to inspect.

This pass creates a Laravel 12 Academics foundation using confirmed database tables from Laravel Boost, while preserving the requested legacy URLs and route names. It intentionally does not invent missing create/update/delete/result/paper-generation behavior.

## Route Mapping

| Legacy URL | CodeIgniter controller | CodeIgniter method | CodeIgniter model method | Database table | Laravel controller | Laravel method | Laravel route name | Laravel model | Blade view | Permission key |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `/admin/academics/acadm` | Missing CI file | `index` | Missing CI file | Multiple | `AcademicsController` | `index` | `admin.academics.dashboard` | Registry summary | `admin.academics.dashboard.index` | `academics` |
| `/admin/academics/book` | Missing CI file | `index` | Missing CI file | `books` | `BookController` | `index` | `admin.academics.books.index` | `Book` | `admin.academics.modules.index` | `book` |
| `/admin/academics/chapter` | Missing CI file | `index` | Missing CI file | `chapter` | `ChapterController` | `index` | `admin.academics.chapters.index` | `Chapter` | `admin.academics.modules.index` | `chapter` |
| `/admin/academics/conference` | Missing CI file | `index` | Missing CI file | `conferences` | `ConferenceController` | `index` | `admin.academics.conferences.index` | `Conference` | `admin.academics.modules.index` | `conference` |
| `/admin/academics/daysetting` | Missing CI file | `index` | Missing CI file | `day` | `DaySettingController` | `index` | `admin.academics.day-settings.index` | `DaySetting` | `admin.academics.modules.index` | `daysetting` |
| `/admin/academics/documentsaca` | Missing CI file | `index` | Missing CI file | `upload_contents` | `AcademicDocumentController` | `index` | `admin.academics.documents.index` | `AcademicDocument` | `admin.academics.modules.index` | `documentsaca` |
| `/admin/academics/domain` | Missing CI file | `index` | Missing CI file | `domain` | `DomainController` | `index` | `admin.academics.domains.index` | `Domain` | `admin.academics.modules.index` | `domain` |
| `/admin/academics/domainmodules` | Missing CI file | `index` | Missing CI file | `domainmodules` | `DomainModuleController` | `index` | `admin.academics.domain-modules.index` | `DomainModule` | `admin.academics.modules.index` | `domainmodules` |
| `/admin/academics/examgroup` | Missing CI file | `index` | Missing CI file | `exam_groups` | `ExamGroupController` | `index` | `admin.academics.exam-groups.index` | `ExamGroup` | `admin.academics.modules.index` | `examgroup` |
| `/admin/academics/examresult` | Missing CI file | `index` | Missing CI file | `exam_group_exam_results` | `ExamResultController` | `index` | `admin.academics.exam-results.index` | `ExamResult` | `admin.academics.modules.index` | `examresult` |
| `/admin/academics/examschedule` | Missing CI file | `index` | Missing CI file | `exam_group_class_batch_exam_subjects` | `ExamScheduleController` | `index` | `admin.academics.exam-schedules.index` | `ExamSchedule` | `admin.academics.modules.index` | `examschedule` |
| `/admin/academics/gmeet` | Missing CI file | `index` | Missing CI file | `gmeet` | `GoogleMeetController` | `index` | `admin.academics.google-meet.index` | `GoogleMeet` | `admin.academics.modules.index` | `gmeet` |
| `/admin/academics/grooming` | Missing CI file | `index` | Missing CI file | `grooming_analysis_results` | `GroomingController` | `index` | `admin.academics.grooming.index` | `Grooming` | `admin.academics.modules.index` | `grooming` |
| `/admin/academics/groomingdomain` | Missing CI file | `index` | Missing CI file | `groomingdomain` | `GroomingDomainController` | `index` | `admin.academics.grooming-domains.index` | `GroomingDomain` | `admin.academics.modules.index` | `groomingdomain` |
| `/admin/academics/groomingparameter` | Missing CI file | `index` | Missing CI file | `groomingparameter` | `GroomingParameterController` | `index` | `admin.academics.grooming-parameters.index` | `GroomingParameter` | `admin.academics.modules.index` | `groomingparameter` |
| `/admin/academics/homework` | Missing CI file | `index` | Missing CI file | `homework` | `HomeworkController` | `index` | `admin.academics.homework.index` | `Homework` | `admin.academics.modules.index` | `homework` |
| `/admin/academics/lesson` | Missing CI file | `index` | Missing CI file | `lesson` | `LessonController` | `index` | `admin.academics.lessons.index` | `Lesson` | `admin.academics.modules.index` | `lesson` |
| `/admin/academics/member` | Missing CI file | `index` | Missing CI file | `libarary_members` | `MemberController` | `index` | `admin.academics.members.index` | `LibraryMember` | `admin.academics.modules.index` | `member` |
| `/admin/academics/onlineexam` | Missing CI file | `index` | Missing CI file | `onlineexam` | `OnlineExamController` | `index` | `admin.academics.online-exams.index` | `OnlineExam` | `admin.academics.modules.index` | `onlineexam` |
| `/admin/academics/papergenerate` | Missing CI file | `index` | Missing CI file | `questions` | `PaperGenerateController` | `index` | `admin.academics.paper-generate.index` | `Question` | `admin.academics.modules.index` | `papergenerate` |
| `/admin/academics/question` | Missing CI file | `index` | Missing CI file | `questions` | `QuestionController` | `index` | `admin.academics.questions.index` | `Question` | `admin.academics.modules.index` | `question` |
| `/admin/academics/subject` | Missing CI file | `index` | Missing CI file | `subjects` | `SubjectController` | `index` | `admin.academics.subjects.index` | `Subject` | `admin.academics.modules.index` | `subject` |
| `/admin/academics/subjectgroup` | Missing CI file | `index` | Missing CI file | `subject_groups` | `SubjectGroupController` | `index` | `admin.academics.subject-groups.index` | `SubjectGroup` | `admin.academics.modules.index` | `subjectgroup` |
| `/admin/academics/syllabus` | Missing CI file | `index` | Missing CI file | `syllabus` | `SyllabusController` | `index` | `admin.academics.syllabus.index` | `Syllabus` | `admin.academics.modules.index` | `syllabus` |
| `/admin/academics/teacher` | Missing CI file | `index` | Missing CI file | `staff` | `TeacherController` | `index` | `admin.academics.teachers.index` | `Staff` | `admin.academics.modules.index` | `teacher` |
| `/admin/academics/termsetting` | Missing CI file | `index` | Missing CI file | `term` | `TermSettingController` | `index` | `admin.academics.term-settings.index` | `TermSetting` | `admin.academics.modules.index` | `termsetting` |
| `/admin/academics/testgroup` | Missing CI file | `index` | Missing CI file | `test_groups` | `TestGroupController` | `index` | `admin.academics.test-groups.index` | `TestGroup` | `admin.academics.modules.index` | `testgroup` |
| `/admin/academics/testresult` | Missing CI file | `index` | Missing CI file | `test_group_test_results` | `TestResultController` | `index` | `admin.academics.test-results.index` | `TestResult` | `admin.academics.modules.index` | `testresult` |
| `/admin/academics/testschedule` | Missing CI file | `index` | Missing CI file | `test_group_class_batch_test_subjects` | `TestScheduleController` | `index` | `admin.academics.test-schedules.index` | `TestSchedule` | `admin.academics.modules.index` | `testschedule` |
| `/admin/academics/timetable` | Missing CI file | `index` | Missing CI file | `subject_timetable` | `TimetableController` | `index` | `admin.academics.timetables.index` | `Timetable` | `admin.academics.modules.index` | `timetable` |
| `/admin/academics/topic` | Missing CI file | `index` | Missing CI file | `topic` | `TopicController` | `index` | `admin.academics.topics.index` | `Topic` | `admin.academics.modules.index` | `topic` |
| `/admin/academics/weeksetting` | Missing CI file | `index` | Missing CI file | `week` | `WeekSettingController` | `index` | `admin.academics.week-settings.index` | `WeekSetting` | `admin.academics.modules.index` | `weeksetting` |

## Unresolved Dependencies

- Original CodeIgniter route file for Academics.
- All CodeIgniter Academics controllers and model methods.
- Original Blade/HTML/PHP views and JavaScript/AJAX/DataTables handlers.
- Exact RBAC privilege short codes where they differ from legacy URL segments.
- Exam/test result calculation routines, grade/rank algorithms, paper generation selection rules, timetable conflict rules, and file upload storage conventions.

## Artisan Commands Used Or Relevant

```bash
php artisan route:list --path=admin/academics
php artisan storage:link
php artisan route:clear
php artisan config:clear
php artisan cache:clear
vendor/bin/pint --dirty --format agent
php artisan test --compact tests/Feature/AcademicsLegacyRoutesTest.php
```
