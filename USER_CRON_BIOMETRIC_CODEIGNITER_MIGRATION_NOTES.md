# User, Cron, and Biometric CodeIgniter Migration Notes

## Route Mapping

| Legacy URL | Laravel route name | Laravel controller |
| --- | --- | --- |
| `/user/user/dashboard` | `user.dashboard` | `User\UserDashboardController@index` |
| `/user/user/profile` | `user.profile.show` | `User\UserProfileController@show` |
| `/user/user/getfees` | `user.fees.index` | `User\UserFeeController@index` |
| `GET /user/user/changepass` | `user.password.edit` | `User\UserPasswordController@edit` |
| `POST /user/user/changepass` | `user.password.update` | `User\UserPasswordController@update` |
| `GET /user/user/changeusername` | `user.username.edit` | `User\UserUsernameController@edit` |
| `POST /user/user/changeusername` | `user.username.update` | `User\UserUsernameController@update` |
| `/user/apply_leave` | `user.leave-requests.index` | `User\LeaveRequestController@index` |
| `/user/attendence` | `user.attendance.index` | `User\AttendanceController@index` |
| `/user/book` | `user.books.index` | `User\BookController@index` |
| `/user/conference` | `user.conferences.index` | `User\ConferenceController@index` |
| `/user/content` | `user.content.index` | `User\ContentController@index` |
| `/user/gmeet` | `user.google-meet.index` | `User\GoogleMeetController@index` |
| `/user/video_tutorial` | `user.video-tutorials.index` | `User\VideoTutorialController@index` |
| `/cron/{key}` | `cron.index` | `Cron\CronController@index` |
| `/cron/biometricattendance/{key}` | `cron.biometric-attendance` | `Cron\CronController@biometricAttendance` |
| `/cron/student_attendance/{key}` | `cron.student-attendance` | `Cron\CronController@studentAttendance` |
| `/cron/autobackup/{key}` | `cron.auto-backup` | `Cron\CronController@autoBackup` |
| `/cron/feereminder/{key}` | `cron.fee-reminder` | `Cron\CronController@feeReminder` |
| `/cron/eventreminder/{key}` | `cron.event-reminder` | `Cron\CronController@eventReminder` |
| `/cron/schedulesmsemails/{key}` | `cron.scheduled-sms-emails` | `Cron\CronController@scheduleSmsEmails` |
| `/biometric` | `biometric.index` | `Biometric\BiometricController@index` |
| `/biometric/getUser` | `biometric.get-user` | `Biometric\BiometricController@getUser` |

## Authentication Findings

- The live `users` table contains `user_id`, `sibling_id`, `username`, `childs`, and `role`.
- Student usernames can be recognized with the legacy `std{id}` pattern.
- Parent usernames can be recognized with the legacy `parent{id}` pattern.
- `UserContext` resolves students from `users.user_id`, parents from `users.sibling_id` and `users.childs`, and validates selected children server-side.

## Implemented Tables

- `students`
- `student_session`
- `student_sibling`
- `student_fees_assign`
- `student_fees_deposite_details`
- `student_attendences`
- `student_applyleave`
- `books`
- `conferences`
- `gmeet`
- `video_tutorial`
- `share_contents` when available

## Unresolved Legacy Dependencies

The original CodeIgniter controller/model source was not present in this Laravel workspace for:

- Exact fee receivable, discount, waive-off, late fine, advance payment, and sibling discount formulas.
- Attendance percentage formulas and biometric duplicate-log rules.
- Leave request create/cancel status transitions.
- Content sharing JSON and download contracts.
- Cron job side effects, cadence, duplicate-prevention tables, SMS/email providers, and backup retention rules.
- Biometric device protocol, authentication mechanism, input payloads, and exact `getUser` response contract.

The Laravel implementation therefore preserves legacy URLs, authentication, context resolution, route names, middleware, basic scoped listings, password and username updates, cron key validation, scheduler entries, and biometric endpoint protection without inventing provider-specific or finance-critical business logic.
