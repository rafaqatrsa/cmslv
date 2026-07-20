# Teacher CodeIgniter Migration Notes

## Route Mapping

| CodeIgniter URL | Laravel route name | Controller |
| --- | --- | --- |
| `/teacher/teacher/dashboard` | `teacher.dashboard` | `Teacher\TeacherDashboardController@index` |
| `/teacher/teacher/profile/{id}` | `teacher.profile.show` | `Teacher\TeacherProfileController@show` |
| `GET /teacher/teacher/changepass` | `teacher.password.edit` | `Teacher\TeacherPasswordController@edit` |
| `POST /teacher/teacher/changepass` | `teacher.password.update` | `Teacher\TeacherPasswordController@update` |
| `/teacher/approve_leave` | `teacher.leave-approvals.index` | `Teacher\LeaveApprovalController@index` |
| `/teacher/conference` | `teacher.conferences.index` | `Teacher\ConferenceController@index` |
| `/teacher/examresult` | `teacher.exam-results.index` | `Teacher\ExamResultController@index` |
| `/teacher/examschedule` | `teacher.exam-schedules.index` | `Teacher\ExamScheduleController@index` |
| `/teacher/gmeet` | `teacher.google-meet.index` | `Teacher\GoogleMeetController@index` |
| `/teacher/grooming` | `teacher.grooming.index` | `Teacher\GroomingController@index` |
| `/teacher/homework` | `teacher.homework.index` | `Teacher\HomeworkController@index` |
| `/teacher/lesson` | `teacher.lessons.index` | `Teacher\LessonController@index` |
| `/teacher/stuattendence` | `teacher.student-attendance.index` | `Teacher\StudentAttendanceController@index` |
| `/teacher/syllabus` | `teacher.syllabus.index` | `Teacher\SyllabusController@index` |
| `/teacher/termsetting` | `teacher.term-settings.index` | `Teacher\TermSettingController@index` |
| `/teacher/testresult` | `teacher.test-results.index` | `Teacher\TestResultController@index` |
| `/teacher/testschedule` | `teacher.test-schedules.index` | `Teacher\TestScheduleController@index` |
| `/teacher/timetable` | `teacher.timetable.index` | `Teacher\TimetableController@index` |

## Migration Scope

- The Laravel routes intentionally preserve the legacy CodeIgniter URL spelling, including `stuattendence`.
- Teacher pages are protected by `auth`, `teacher`, and `branch` middleware.
- `TeacherContext` resolves the authenticated teacher from `staff.user_id`, branch from `staff.brc_id`, and the active academic session from the existing session context.
- Teacher-owned listings filter by `staff_id` when that column exists, then by `brc_id` and `session_id` when available.
- Legacy tables that are not yet migrated return empty paginated results instead of failing, allowing the module to be converted incrementally.

## Missing CodeIgniter Source

The original CodeIgniter controller/model source for these teacher workflows was not present in this Laravel workspace. The migration therefore implements production-safe route compatibility, authentication, teacher scoping, views, and password handling, but does not invent workflow-specific create/update/approval business logic.
