<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    $this->withoutVite();
});

it('requires authentication for hrms legacy urls', function () {
    $this->get('/admin/hrms/hrm')->assertRedirect('/login');
});

it('renders every hrms legacy url for authenticated admin users', function (string $uri) {
    $user = User::factory()->create();

    $this->actingAs($user)->get($uri)->assertSuccessful();
})->with([
    '/admin/hrms/documentshrm',
    '/admin/hrms/hrm',
    '/admin/hrms/manualhrm',
    '/admin/hrms/staff',
]);

it('renders the hrms staff page with the tailored search and records sections', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/admin/hrms/staff')
        ->assertSuccessful()
        ->assertSee('Select Criteria')
        ->assertSee('Search By Keyword')
        ->assertSee('List View')
        ->assertSee('Branch')
        ->assertSee('Action')
        ->assertSee('Add Staff');
});

it('registers the required hrms route names', function (string $routeName) {
    expect(Route::has($routeName))->toBeTrue();
})->with([
    'admin.hrms.dashboard',
    'admin.hrms.documents.index',
    'admin.hrms.manual.index',
    'admin.hrms.staff.index',
    'admin.hrms.staff.profile',
    'admin.hrms.staff.edit',
    'admin.hrms.staff.update',
    'admin.hrms.staff.appointment-form',
    'admin.hrms.staff.service-experience-certificate',
]);
