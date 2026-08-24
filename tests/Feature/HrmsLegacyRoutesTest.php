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

it('registers the required hrms route names', function (string $routeName) {
    expect(Route::has($routeName))->toBeTrue();
})->with([
    'admin.hrms.dashboard',
    'admin.hrms.documents.index',
    'admin.hrms.manual.index',
    'admin.hrms.staff.index',
]);
