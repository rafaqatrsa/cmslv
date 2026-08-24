<?php

use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Config::set('auth.providers.users.model', User::class);
    Auth::forgetGuards();
});

it('renders staff authentication pages from legacy and compatible urls', function (string $uri) {
    $this->get($uri)->assertSuccessful();
})->with([
    '/login',
    '/staff/login',
    '/superadmin/login',
    '/staff/forgotpassword',
]);

it('renders site sign in pages from legacy and compatible urls', function (string $uri) {
    $this->get($uri)->assertSuccessful();
})->with([
    '/signin',
    '/site/signin',
]);

it('loads built assets and posts superadmin login to the superadmin alias', function () {
    $this->get('/superadmin/login')
        ->assertSuccessful()
        ->assertSee('/build/assets/app-', false)
        ->assertSee('action="'.route('superadmin.login', absolute: false).'"', false);
});

it('authenticates staff users from both login urls', function (string $uri) {
    $user = User::factory()->create();

    $response = $this->post($uri, [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route('admin.dashboard', absolute: false));
})->with([
    '/login',
    '/staff/login',
    '/superadmin/login',
]);

it('authenticates superadmin login against the legacy staff table when staff is the auth model', function () {
    Schema::create('staff', function (Blueprint $table): void {
        $table->id();
        $table->unsignedBigInteger('brc_id')->nullable();
        $table->unsignedBigInteger('role_id')->nullable();
        $table->string('employee_id')->nullable();
        $table->string('name')->nullable();
        $table->string('email')->nullable();
        $table->string('password');
        $table->integer('is_active')->default(1);
        $table->timestamps();
    });

    Config::set('auth.providers.users.model', Staff::class);
    Auth::forgetGuards();

    $staff = Staff::query()->create([
        'email' => 'superadmin@example.com',
        'employee_id' => 'SA-001',
        'name' => 'Super Admin',
        'password' => Hash::make('password'),
        'is_active' => 1,
    ]);

    $response = $this->post('/superadmin/login', [
        'email' => 'superadmin@example.com',
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($staff);
    $response->assertRedirect(route('admin.dashboard', absolute: false));
});

it('authenticates site users from both sign in urls', function (string $uri) {
    $user = User::factory()->create([
        'email' => 'student@example.com',
    ]);

    $response = $this->post($uri, [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($user, 'site');
    $response->assertRedirect(route('user.dashboard', absolute: false));
})->with([
    '/signin',
    '/site/signin',
]);

it('validates staff login submissions', function () {
    $this->post('/login', [
        'email' => 'not-an-email',
        'password' => '',
    ])->assertSessionHasErrors(['email', 'password']);

    $this->assertGuest();
});

it('logs out staff users and redirects to staff login', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/staff/logout');

    $this->assertGuest();
    $response->assertRedirect(route('staff.login', absolute: false));
});

it('logs out site users and redirects to site sign in', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'site')->post('/site/logout');

    $this->assertGuest('site');
    $response->assertRedirect(route('site.signin', absolute: false));
});
