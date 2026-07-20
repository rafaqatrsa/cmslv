<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    $this->withoutVite();
});

it('requires authentication for academics legacy urls', function () {
    $this->get('/admin/academics/acadm')->assertRedirect('/login');
});

it('renders every academics legacy url for authenticated admin users', function (string $uri) {
    $user = User::factory()->create();

    $this->actingAs($user)->get($uri)->assertSuccessful();
})->with([
    '/admin/academics/acadm',
    '/admin/academics/book',
    '/admin/academics/chapter',
    '/admin/academics/conference',
    '/admin/academics/daysetting',
    '/admin/academics/documentsaca',
    '/admin/academics/domain',
    '/admin/academics/domainmodules',
    '/admin/academics/examgroup',
    '/admin/academics/examresult',
    '/admin/academics/examschedule',
    '/admin/academics/gmeet',
    '/admin/academics/grooming',
    '/admin/academics/groomingdomain',
    '/admin/academics/groomingparameter',
    '/admin/academics/homework',
    '/admin/academics/lesson',
    '/admin/academics/member',
    '/admin/academics/onlineexam',
    '/admin/academics/papergenerate',
    '/admin/academics/question',
    '/admin/academics/subject',
    '/admin/academics/subjectgroup',
    '/admin/academics/syllabus',
    '/admin/academics/teacher',
    '/admin/academics/termsetting',
    '/admin/academics/testgroup',
    '/admin/academics/testresult',
    '/admin/academics/testschedule',
    '/admin/academics/timetable',
    '/admin/academics/topic',
    '/admin/academics/weeksetting',
]);

it('registers the required route names', function (string $routeName) {
    expect(Route::has($routeName))->toBeTrue();
})->with([
    'admin.academics.dashboard',
    'admin.academics.books.index',
    'admin.academics.chapters.index',
    'admin.academics.conferences.index',
    'admin.academics.day-settings.index',
    'admin.academics.documents.index',
    'admin.academics.domains.index',
    'admin.academics.domain-modules.index',
    'admin.academics.exam-groups.index',
    'admin.academics.exam-results.index',
    'admin.academics.exam-schedules.index',
    'admin.academics.google-meet.index',
    'admin.academics.grooming.index',
    'admin.academics.grooming-domains.index',
    'admin.academics.grooming-parameters.index',
    'admin.academics.homework.index',
    'admin.academics.lessons.index',
    'admin.academics.members.index',
    'admin.academics.online-exams.index',
    'admin.academics.paper-generate.index',
    'admin.academics.questions.index',
    'admin.academics.subjects.index',
    'admin.academics.subject-groups.index',
    'admin.academics.syllabus.index',
    'admin.academics.teachers.index',
    'admin.academics.term-settings.index',
    'admin.academics.test-groups.index',
    'admin.academics.test-results.index',
    'admin.academics.test-schedules.index',
    'admin.academics.timetables.index',
    'admin.academics.topics.index',
    'admin.academics.week-settings.index',
]);
