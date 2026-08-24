<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    $this->withoutVite();
});

it('requires authentication for adm legacy urls', function () {
    $this->get('/admin/adm/admn')->assertRedirect('/login');
});

it('renders every adm legacy url for authenticated admin users', function (string $uri) {
    $user = User::factory()->create();

    $this->actingAs($user)->get($uri)->assertSuccessful();
})->with([
    '/admin/adm/admn',
    '/admin/adm/achievement',
    '/admin/adm/approve_leave',
    '/admin/adm/attendance',
    '/admin/adm/calendar',
    '/admin/adm/chat',
    '/admin/adm/complaint',
    '/admin/adm/complaintregarding',
    '/admin/adm/complaintsource',
    '/admin/adm/complainttype',
    '/admin/adm/content',
    '/admin/adm/contenttype',
    '/admin/adm/dispatch',
    '/admin/adm/documentsadm',
    '/admin/adm/enquiry',
    '/admin/adm/generalcall',
    '/admin/adm/generalremarks',
    '/admin/adm/generateidcard',
    '/admin/adm/generatestaffidcard',
    '/admin/adm/leaverequest',
    '/admin/adm/mailsms',
    '/admin/adm/notification',
    '/admin/adm/receive',
    '/admin/adm/reference',
    '/admin/adm/siblings',
    '/admin/adm/source',
    '/admin/adm/staffattendance',
    '/admin/adm/staffidcard',
    '/admin/adm/stdtransferclasssection',
    '/admin/adm/student/stdtransfer',
    '/admin/adm/stuattendence',
    '/admin/adm/student',
    '/admin/adm/student/search',
    '/admin/adm/student/create',
    '/admin/adm/student_regd',
    '/admin/adm/promote_student',
    '/admin/adm/studentidcard',
    '/admin/adm/subjectattendence',
    '/admin/adm/videotutorial',
    '/admin/adm/visitorspurpose',
]);

it('registers the required adm route names', function (string $routeName) {
    expect(Route::has($routeName))->toBeTrue();
})->with([
    'admin.adm.dashboard',
    'admin.adm.achievements.index',
    'admin.adm.leave-approvals.index',
    'admin.adm.attendance.index',
    'admin.adm.calendar.index',
    'admin.adm.chat.index',
    'admin.adm.complaints.index',
    'admin.adm.complaint-regardings.index',
    'admin.adm.complaint-sources.index',
    'admin.adm.complaint-types.index',
    'admin.adm.content.index',
    'admin.adm.content-types.index',
    'admin.adm.dispatch.index',
    'admin.adm.documents.index',
    'admin.adm.enquiries.index',
    'admin.adm.general-calls.index',
    'admin.adm.general-remarks.index',
    'admin.adm.id-card-generator.index',
    'admin.adm.staff-id-card-generator.index',
    'admin.adm.leave-requests.index',
    'admin.adm.mail-sms.index',
    'admin.adm.notifications.index',
    'admin.adm.receive.index',
    'admin.adm.references.index',
    'admin.adm.siblings.index',
    'admin.adm.sources.index',
    'admin.adm.staff-attendance.index',
    'admin.adm.staff-id-cards.index',
    'admin.adm.student-transfers.index',
    'admin.adm.student-transfers.legacy',
    'admin.adm.student-attendance.index',
    'admin.adm.students.index',
    'admin.adm.students.search',
    'admin.adm.student-admissions.create',
    'admin.adm.student-admissions.store',
    'admin.adm.student-registrations.index',
    'admin.adm.student-promotions.index',
    'admin.adm.student-promotions.store',
    'admin.adm.student-id-cards.index',
    'admin.adm.subject-attendance.index',
    'admin.adm.video-tutorials.index',
    'admin.adm.visitor-purposes.index',
]);
