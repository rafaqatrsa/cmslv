<?php

namespace App\Services\Adm;

use App\Models\Adm\Achievement;
use App\Models\Adm\AdmDocument;
use App\Models\Adm\CalendarEvent;
use App\Models\Adm\ChatMessage;
use App\Models\Adm\Complaint;
use App\Models\Adm\ComplaintRegarding;
use App\Models\Adm\ComplaintSource;
use App\Models\Adm\ComplaintType;
use App\Models\Adm\Content;
use App\Models\Adm\ContentType;
use App\Models\Adm\DispatchRecord;
use App\Models\Adm\Enquiry;
use App\Models\Adm\GeneralCall;
use App\Models\Adm\GeneralRemark;
use App\Models\Adm\LeaveRequest;
use App\Models\Adm\Notification;
use App\Models\Adm\ReceiveRecord;
use App\Models\Adm\Reference;
use App\Models\Adm\Sibling;
use App\Models\Adm\Source;
use App\Models\Adm\StaffAttendance;
use App\Models\Adm\StaffIdCard;
use App\Models\Adm\Student;
use App\Models\Adm\StudentAttendance;
use App\Models\Adm\StudentIdCard;
use App\Models\Adm\StudentRegistration;
use App\Models\Adm\StudentSession;
use App\Models\Adm\SubjectAttendance;
use App\Models\Adm\VideoTutorial;
use App\Models\Adm\VisitorPurpose;

class AdmModuleRegistry
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public function all(): array
    {
        return [
            'dashboard' => $this->module('ADM Dashboard', Student::class, 'students', 'admin.adm.dashboard', 'admn', ['admission_no', 'firstname', 'lastname'], ['admission_no', 'firstname', 'lastname', 'mobileno', 'is_active']),
            'achievements' => $this->module('Achievements', Achievement::class, 'student_timeline', 'admin.adm.achievements.index', 'achievement', ['title', 'description'], ['student_id', 'title', 'timeline_date', 'status']),
            'leave-approvals' => $this->module('Leave Approvals', LeaveRequest::class, 'student_applyleave', 'admin.adm.leave-approvals.index', 'approve_leave', ['reason'], ['student_session_id', 'from_date', 'to_date', 'status', 'approve_date']),
            'attendance' => $this->module('Attendance', StudentAttendance::class, 'student_attendences', 'admin.adm.attendance.index', 'attendance', ['remark'], ['student_session_id', 'date', 'attendence_type_id', 'remark', 'is_active']),
            'calendar' => $this->module('Calendar', CalendarEvent::class, 'front_cms_programs', 'admin.adm.calendar.index', 'calendar', ['title', 'description'], ['title', 'date', 'event_start', 'event_end']),
            'chat' => $this->module('Chat', ChatMessage::class, 'chat_messages', 'admin.adm.chat.index', 'chat', ['message'], ['chat_user_id', 'message', 'is_read', 'created_at']),
            'complaints' => $this->module('Complaints', Complaint::class, 'complaint', 'admin.adm.complaints.index', 'complaint', ['complaint_no', 'name', 'subject', 'description'], ['complaint_no', 'name', 'date', 'status', 'priority']),
            'complaint-regardings' => $this->module('Complaint Regardings', ComplaintRegarding::class, 'complaint_regarding', 'admin.adm.complaint-regardings.index', 'complaintregarding', ['name', 'note'], ['name', 'note', 'is_active']),
            'complaint-sources' => $this->module('Complaint Sources', ComplaintSource::class, 'complaint_source', 'admin.adm.complaint-sources.index', 'complaintsource', ['name', 'note'], ['name', 'note', 'is_active']),
            'complaint-types' => $this->module('Complaint Types', ComplaintType::class, 'complaint_type', 'admin.adm.complaint-types.index', 'complainttype', ['name', 'note'], ['name', 'note', 'is_active']),
            'content' => $this->module('Content', Content::class, 'share_contents', 'admin.adm.content.index', 'content', ['title', 'description'], ['title', 'share_date', 'valid_upto', 'created_by']),
            'content-types' => $this->module('Content Types', ContentType::class, 'content_types', 'admin.adm.content-types.index', 'contenttype', ['name', 'description'], ['name', 'description', 'is_active']),
            'dispatch' => $this->module('Dispatch', DispatchRecord::class, 'dispatch_receive', 'admin.adm.dispatch.index', 'dispatch', ['reference_no', 'to_title', 'note'], ['reference_no', 'to_title', 'date', 'created_by']),
            'documents' => $this->module('ADM Documents', AdmDocument::class, 'upload_contents', 'admin.adm.documents.index', 'documentsadm', ['real_name', 'img_name', 'vid_title'], ['real_name', 'file_type', 'file_size', 'created_at']),
            'enquiries' => $this->module('Enquiries', Enquiry::class, 'enquiry', 'admin.adm.enquiries.index', 'enquiry', ['enquiry_no', 'name', 'contact', 'description'], ['enquiry_no', 'name', 'contact', 'date', 'status']),
            'general-calls' => $this->module('General Calls', GeneralCall::class, 'general_calls', 'admin.adm.general-calls.index', 'generalcall', ['name', 'contact', 'description'], ['name', 'contact', 'date', 'follow_up_date']),
            'general-remarks' => $this->module('General Remarks', GeneralRemark::class, 'general_remarks', 'admin.adm.general-remarks.index', 'generalremarks', ['general_remarks_no', 'subject', 'description'], ['general_remarks_no', 'date', 'status', 'priority']),
            'id-card-generator' => $this->module('Student ID Card Generator', StudentIdCard::class, 'students_id_card', 'admin.adm.id-card-generator.index', 'generateidcard', ['title', 'school_name'], ['title', 'school_name', 'status']),
            'staff-id-card-generator' => $this->module('Staff ID Card Generator', StaffIdCard::class, 'staff_id_card', 'admin.adm.staff-id-card-generator.index', 'generatestaffidcard', ['title', 'school_name'], ['title', 'school_name', 'status']),
            'leave-requests' => $this->module('Leave Requests', LeaveRequest::class, 'student_applyleave', 'admin.adm.leave-requests.index', 'leaverequest', ['reason'], ['student_session_id', 'from_date', 'to_date', 'apply_date', 'status']),
            'mail-sms' => $this->module('Mail SMS', Notification::class, 'send_notification', 'admin.adm.mail-sms.index', 'mailsms', ['title', 'message'], ['title', 'publish_date', 'visible_student', 'visible_staff', 'visible_parent']),
            'notifications' => $this->module('Notifications', Notification::class, 'send_notification', 'admin.adm.notifications.index', 'notification', ['title', 'message'], ['title', 'publish_date', 'date', 'is_active']),
            'receive' => $this->module('Receive', ReceiveRecord::class, 'dispatch_receive', 'admin.adm.receive.index', 'receive', ['reference_no', 'from_title', 'note'], ['reference_no', 'from_title', 'date', 'created_by']),
            'references' => $this->module('References', Reference::class, 'reference', 'admin.adm.references.index', 'reference', ['reference', 'description'], ['reference', 'description', 'status']),
            'siblings' => $this->module('Siblings', Sibling::class, 'student_sibling', 'admin.adm.siblings.index', 'siblings', ['sibling_name', 'sibling_cnic', 'sibling_phone'], ['sibling_code', 'sibling_name', 'sibling_phone', 'is_active']),
            'sources' => $this->module('Sources', Source::class, 'source', 'admin.adm.sources.index', 'source', ['source', 'source_category', 'description'], ['source', 'source_category', 'status']),
            'staff-attendance' => $this->module('Staff Attendance', StaffAttendance::class, 'staff_attendance', 'admin.adm.staff-attendance.index', 'staffattendance', ['remark'], ['staff_id', 'date', 'staff_attendance_type_id', 'remark']),
            'staff-id-cards' => $this->module('Staff ID Cards', StaffIdCard::class, 'staff_id_card', 'admin.adm.staff-id-cards.index', 'staffidcard', ['title', 'school_name'], ['title', 'school_name', 'status']),
            'student-transfers' => $this->module('Student Transfers', StudentSession::class, 'student_session', 'admin.adm.student-transfers.index', 'stdtransferclasssection', ['student_id'], ['student_id', 'class_id', 'section_id', 'session_id', 'is_active']),
            'student-attendance' => $this->module('Student Attendance', StudentAttendance::class, 'student_attendences', 'admin.adm.student-attendance.index', 'stuattendence', ['remark'], ['student_session_id', 'date', 'attendence_type_id', 'remark']),
            'students' => $this->module('Students', Student::class, 'students', 'admin.adm.students.index', 'student', ['admission_no', 'firstname', 'lastname', 'father_name', 'mobileno'], ['admission_no', 'firstname', 'lastname', 'father_name', 'is_active']),
            'student-registrations' => $this->module('Student Registrations', StudentRegistration::class, 'students_regd', 'admin.adm.student-registrations.index', 'student_regd', ['regd_no', 'firstname', 'lastname', 'father_name'], ['regd_no', 'regd_date', 'firstname', 'father_name', 'regd_status']),
            'student-id-cards' => $this->module('Student ID Cards', StudentIdCard::class, 'students_id_card', 'admin.adm.student-id-cards.index', 'studentidcard', ['title', 'school_name'], ['title', 'school_name', 'status']),
            'subject-attendance' => $this->module('Subject Attendance', SubjectAttendance::class, 'student_subject_attendances', 'admin.adm.subject-attendance.index', 'subjectattendence', ['remark'], ['student_session_id', 'subject_timetable_id', 'date', 'attendence_type_id']),
            'video-tutorials' => $this->module('Video Tutorials', VideoTutorial::class, 'video_tutorial', 'admin.adm.video-tutorials.index', 'videotutorial', ['title', 'vid_title', 'description'], ['title', 'vid_title', 'video_link', 'created_at']),
            'visitor-purposes' => $this->module('Visitor Purposes', VisitorPurpose::class, 'visitors_purpose', 'admin.adm.visitor-purposes.index', 'visitorspurpose', ['visitors_purpose', 'description'], ['visitors_purpose', 'description']),
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
