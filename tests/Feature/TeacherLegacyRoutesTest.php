<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    $this->withoutVite();
});

it('requires authentication for teacher legacy urls', function () {
    $this->get('/teacher/teacher/dashboard')->assertRedirect('/login');
});

it('renders every teacher legacy url for authenticated teacher users', function (string $uri) {
    $user = User::factory()->create();

    $this->actingAs($user)->get($uri)->assertSuccessful();
})->with([
    '/teacher/teacher/dashboard',
    '/teacher/teacher/profile/1',
    '/teacher/teacher/changepass',
    '/teacher/approve_leave',
    '/teacher/conference',
    '/teacher/examresult',
    '/teacher/examschedule',
    '/teacher/gmeet',
    '/teacher/grooming',
    '/teacher/homework',
    '/teacher/lesson',
    '/teacher/stuattendence',
    '/teacher/syllabus',
    '/teacher/termsetting',
    '/teacher/testresult',
    '/teacher/testschedule',
    '/teacher/timetable',
]);

it('registers the required teacher route names', function (string $routeName) {
    expect(Route::has($routeName))->toBeTrue();
})->with([
    'teacher.dashboard',
    'teacher.profile.show',
    'teacher.password.edit',
    'teacher.password.update',
    'teacher.leave-approvals.index',
    'teacher.conferences.index',
    'teacher.exam-results.index',
    'teacher.exam-schedules.index',
    'teacher.google-meet.index',
    'teacher.grooming.index',
    'teacher.homework.index',
    'teacher.lessons.index',
    'teacher.student-attendance.index',
    'teacher.syllabus.index',
    'teacher.term-settings.index',
    'teacher.test-results.index',
    'teacher.test-schedules.index',
    'teacher.timetable.index',
]);

it('updates teacher password with the current password', function () {
    $user = User::factory()->create([
        'password' => Hash::make('old-password'),
    ]);

    $this->actingAs($user)
        ->post('/teacher/teacher/changepass', [
            'current_password' => 'old-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])
        ->assertRedirect(route('teacher.password.edit'));

    expect(Hash::check('new-password', $user->refresh()->password))->toBeTrue();
});
