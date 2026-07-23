<?php

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\ViewErrorBag;

test('guests are redirected to the login page', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

test('authenticated users can visit the dashboard', function () {
    $this->actingAs($user = User::factory()->create());

    $this->get('/dashboard')->assertRedirect('/admin/admin/dashboard');
});

test('authenticated admins see clickable legacy dashboard tabs', function () {
    $this->actingAs(User::factory()->create());

    $html = view('admin.dashboard.index', [
        'stats' => [],
        'latestNotifications' => new Collection,
        'errors' => new ViewErrorBag,
    ])->render();

    expect($html)
        ->toContain('href="#dashboard"')
        ->toContain('href="#modules"')
        ->toContain('href="#reports"')
        ->toContain('href="#cms"')
        ->toContain('href="#lms"')
        ->toContain('href="'.route('admin.systemsettings.dashboard', absolute: false).'"')
        ->toContain('HRMS')
        ->toContain('ACCOUNTS &amp; FINANCE');
});

test('authenticated admins can open the legacy-style system settings dashboard route', function () {
    $this->actingAs(User::factory()->create())
        ->get('/admin/setting/systemsettings/dashboard')
        ->assertSuccessful()
        ->assertSee('SYSTEM SETTINGS')
        ->assertSee('General Settings')
        ->assertSee('Front CMS Setting');
});
