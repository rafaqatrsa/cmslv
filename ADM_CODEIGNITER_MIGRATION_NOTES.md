# ADM / Student Affairs CodeIgniter Migration Notes

The Laravel workspace does not contain the original CodeIgniter ADM controllers, models, views, helpers, libraries, JavaScript files, print templates, AJAX endpoints, or report SQL. A workspace search for `/admin/adm`, `student_regd`, `stuattendence`, `subjectattendence`, `approve_leave`, and CodeIgniter branch/session helpers returned no ADM source files.

This implementation preserves the known legacy URLs and creates a Laravel 12, table-backed conversion foundation. It does not invent student admission, attendance, transfer, leave, communication, ID-card, or notification business rules that require the missing CodeIgniter source.

## Migration Map

| Legacy URL | CodeIgniter controller | CodeIgniter method | CodeIgniter model method | Database tables | Laravel controller | Laravel method | Laravel route name | Laravel model or service | Blade view | Permission key | AJAX or form dependency |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `/admin/adm/admn` | Missing source | `index` | Missing source | `students` | `AdmDashboardController` | `index` | `admin.adm.dashboard` | `AdmIndexService` | `admin.adm.modules.index` | `admn` | Missing dashboard widgets |
| `/admin/adm/achievement` | Missing source | `index` | Missing source | `student_timeline` | `AchievementController` | `index` | `admin.adm.achievements.index` | `Achievement` | `admin.adm.modules.index` | `achievement` | Missing upload/form workflow |
| `/admin/adm/approve_leave` | Missing source | `index` | Missing source | `student_applyleave` | `LeaveApprovalController` | `index` | `admin.adm.leave-approvals.index` | `LeaveRequest` | `admin.adm.modules.index` | `approve_leave` | Missing approval/rejection workflow |
| `/admin/adm/attendance` | Missing source | `index` | Missing source | `student_attendences`, `attendence_type` | `AttendanceController` | `index` | `admin.adm.attendance.index` | `StudentAttendance`, `AttendanceService` | `admin.adm.modules.index` | `attendance` | Missing bulk attendance forms |
| `/admin/adm/calendar` | Missing source | `index` | Missing source | `front_cms_programs` not found in schema filter | `CalendarController` | `index` | `admin.adm.calendar.index` | `CalendarEvent` | `admin.adm.modules.index` | `calendar` | Missing event UI |
| `/admin/adm/chat` | Missing source | `index` | Missing source | `chat_messages`, `chat_connections`, `chat_users` | `ChatController` | `index` | `admin.adm.chat.index` | `ChatMessage` | `admin.adm.modules.index` | `chat` | Missing live chat endpoints |
| `/admin/adm/complaint` | Missing source | `index` | Missing source | `complaint`, `complaint_follow_up` | `ComplaintController` | `index` | `admin.adm.complaints.index` | `Complaint` | `admin.adm.modules.index` | `complaint` | Missing workflow/status AJAX |
| `/admin/adm/complaintregarding` | Missing source | `index` | Missing source | `complaint_regarding` | `ComplaintRegardingController` | `index` | `admin.adm.complaint-regardings.index` | `ComplaintRegarding` | `admin.adm.modules.index` | `complaintregarding` | Missing CRUD forms |
| `/admin/adm/complaintsource` | Missing source | `index` | Missing source | `complaint_source` | `ComplaintSourceController` | `index` | `admin.adm.complaint-sources.index` | `ComplaintSource` | `admin.adm.modules.index` | `complaintsource` | Missing CRUD forms |
| `/admin/adm/complainttype` | Missing source | `index` | Missing source | `complaint_type` | `ComplaintTypeController` | `index` | `admin.adm.complaint-types.index` | `ComplaintType` | `admin.adm.modules.index` | `complainttype` | Missing CRUD forms |
| `/admin/adm/content` | Missing source | `index` | Missing source | `share_contents`, `share_content_for`, `upload_contents` | `ContentController` | `index` | `admin.adm.content.index` | `Content` | `admin.adm.modules.index` | `content` | Missing recipient/upload UI |
| `/admin/adm/contenttype` | Missing source | `index` | Missing source | `content_types` | `ContentTypeController` | `index` | `admin.adm.content-types.index` | `ContentType` | `admin.adm.modules.index` | `contenttype` | Missing CRUD forms |
| `/admin/adm/dispatch` | Missing source | `index` | Missing source | `dispatch_receive` not found in schema filter | `DispatchController` | `index` | `admin.adm.dispatch.index` | `DispatchRecord` | `admin.adm.modules.index` | `dispatch` | Missing attachment workflow |
| `/admin/adm/documentsadm` | Missing source | `index` | Missing source | `upload_contents` | `AdmDocumentController` | `index` | `admin.adm.documents.index` | `AdmDocument` | `admin.adm.modules.index` | `documentsadm` | Missing upload categorization |
| `/admin/adm/enquiry` | Missing source | `index` | Missing source | `enquiry`, `enquiry_kid` | `EnquiryController` | `index` | `admin.adm.enquiries.index` | `Enquiry` | `admin.adm.modules.index` | `enquiry` | Missing follow-up workflow |
| `/admin/adm/generalcall` | Missing source | `index` | Missing source | `general_calls` not found in schema filter | `GeneralCallController` | `index` | `admin.adm.general-calls.index` | `GeneralCall` | `admin.adm.modules.index` | `generalcall` | Missing call form |
| `/admin/adm/generalremarks` | Missing source | `index` | Missing source | `general_remarks` | `GeneralRemarkController` | `index` | `admin.adm.general-remarks.index` | `GeneralRemark` | `admin.adm.modules.index` | `generalremarks` | Missing workflow forms |
| `/admin/adm/generateidcard` | Missing source | `index` | Missing source | `students_id_card` | `IdCardGeneratorController` | `index` | `admin.adm.id-card-generator.index` | `StudentIdCard` | `admin.adm.modules.index` | `generateidcard` | Missing bulk print logic |
| `/admin/adm/generatestaffidcard` | Missing source | `index` | Missing source | `staff_id_card` | `StaffIdCardGeneratorController` | `index` | `admin.adm.staff-id-card-generator.index` | `StaffIdCard` | `admin.adm.modules.index` | `generatestaffidcard` | Missing bulk print logic |
| `/admin/adm/leaverequest` | Missing source | `index` | Missing source | `student_applyleave` | `LeaveRequestController` | `index` | `admin.adm.leave-requests.index` | `LeaveRequest` | `admin.adm.modules.index` | `leaverequest` | Missing submission workflow |
| `/admin/adm/mailsms` | Missing source | `index` | Missing source | `send_notification`, `notification_setting` | `MailSmsController` | `index` | `admin.adm.mail-sms.index` | `Notification` | `admin.adm.modules.index` | `mailsms` | Missing mail/SMS providers |
| `/admin/adm/notification` | Missing source | `index` | Missing source | `send_notification`, `system_notification` | `NotificationController` | `index` | `admin.adm.notifications.index` | `Notification` | `admin.adm.modules.index` | `notification` | Missing recipient rules |
| `/admin/adm/receive` | Missing source | `index` | Missing source | `dispatch_receive` not found in schema filter | `ReceiveController` | `index` | `admin.adm.receive.index` | `ReceiveRecord` | `admin.adm.modules.index` | `receive` | Missing receive workflow |
| `/admin/adm/reference` | Missing source | `index` | Missing source | `reference` | `ReferenceController` | `index` | `admin.adm.references.index` | `Reference` | `admin.adm.modules.index` | `reference` | Missing CRUD forms |
| `/admin/adm/siblings` | Missing source | `index` | Missing source | `student_sibling`, `student_session` | `SiblingController` | `index` | `admin.adm.siblings.index` | `Sibling` | `admin.adm.modules.index` | `siblings` | Missing parent-login rules |
| `/admin/adm/source` | Missing source | `index` | Missing source | `source` | `SourceController` | `index` | `admin.adm.sources.index` | `Source` | `admin.adm.modules.index` | `source` | Missing CRUD forms |
| `/admin/adm/staffattendance` | Missing source | `index` | Missing source | `staff_attendance`, `staff_attendance_type` | `StaffAttendanceController` | `index` | `admin.adm.staff-attendance.index` | `StaffAttendance` | `admin.adm.modules.index` | `staffattendance` | Missing bulk staff attendance workflow |
| `/admin/adm/staffidcard` | Missing source | `index` | Missing source | `staff_id_card` | `StaffIdCardController` | `index` | `admin.adm.staff-id-cards.index` | `StaffIdCard` | `admin.adm.modules.index` | `staffidcard` | Missing template editor |
| `/admin/adm/stdtransferclasssection` | Missing source | `index` | Missing source | `student_session` | `StudentTransferController` | `index` | `admin.adm.student-transfers.index` | `StudentTransferService` | `admin.adm.modules.index` | `stdtransferclasssection` | Missing full transfer side effects |
| `/admin/adm/stuattendence` | Missing source | `index` | Missing source | `student_attendences` | `StudentAttendanceController` | `index` | `admin.adm.student-attendance.index` | `StudentAttendance`, `AttendanceService` | `admin.adm.modules.index` | `stuattendence` | Missing attendance grid |
| `/admin/adm/student` | Missing source | `index` | Missing source | `students`, `student_session`, `student_doc` | `StudentController` | `index` | `admin.adm.students.index` | `Student` | `admin.adm.modules.index` | `student` | Missing admission/profile forms |
| `/admin/adm/student_regd` | Missing source | `index` | Missing source | `students_regd`, `student_regd_fee` | `StudentRegistrationController` | `index` | `admin.adm.student-registrations.index` | `StudentRegistration` | `admin.adm.modules.index` | `student_regd` | Missing registration-number generator |
| `/admin/adm/studentidcard` | Missing source | `index` | Missing source | `students_id_card` | `StudentIdCardController` | `index` | `admin.adm.student-id-cards.index` | `StudentIdCard` | `admin.adm.modules.index` | `studentidcard` | Missing template editor |
| `/admin/adm/subjectattendence` | Missing source | `index` | Missing source | `student_subject_attendances` | `SubjectAttendanceController` | `index` | `admin.adm.subject-attendance.index` | `SubjectAttendance`, `AttendanceService` | `admin.adm.modules.index` | `subjectattendence` | Missing subject attendance grid |
| `/admin/adm/videotutorial` | Missing source | `index` | Missing source | `video_tutorial`, `video_tutorial_class_sections` | `VideoTutorialController` | `index` | `admin.adm.video-tutorials.index` | `VideoTutorial` | `admin.adm.modules.index` | `videotutorial` | Missing video modal/player JS |
| `/admin/adm/visitorspurpose` | Missing source | `index` | Missing source | `visitors_purpose` | `VisitorPurposeController` | `index` | `admin.adm.visitor-purposes.index` | `VisitorPurpose` | `admin.adm.modules.index` | `visitorspurpose` | Missing CRUD forms |

## Implemented Laravel Files

- Routes: `routes/web.php`
- Controllers: `app/Http/Controllers/Admin/Adm/*Controller.php`
- Models: `app/Models/Adm/*.php`
- Services: `app/Services/Adm/AdmIndexService.php`, `AdmModuleRegistry.php`, `StudentTransferService.php`, `AttendanceService.php`
- Views: `resources/views/admin/adm/partials/nav.blade.php`, `resources/views/admin/adm/modules/index.blade.php`
- Tests: `tests/Feature/AdmLegacyRoutesTest.php`, `tests/Unit/AdmModuleRegistryTest.php`

## Unresolved Dependencies

- Original CodeIgniter ADM controllers, models, views, helpers, libraries, and JavaScript
- Full method list beyond the top-level legacy URLs
- AJAX/DataTables request and response contracts
- Registration-number generation rules
- Student admission, sibling, parent-login, and credential workflows
- Student transfer side effects for fees, attendance, subject groups, history, and roll numbers
- Leave approval side effects
- Mail, SMS, WhatsApp, and push notification providers
- ID-card print layout, QR/barcode content, and CSS dimensions
- Dispatch/receive table details; no matching table was found by schema filters
- Calendar/general-call table details; no matching table was found by schema filters

## Artisan Commands Used Or Relevant

```bash
php artisan route:list --path=admin/adm
vendor/bin/pint --dirty --format agent
php artisan test --compact tests/Feature/AdmLegacyRoutesTest.php tests/Unit/AdmModuleRegistryTest.php
```
