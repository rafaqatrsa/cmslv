<?php

use App\Models\User;

beforeEach(function () {
    $this->withoutVite();
});

test('admission dashboard renders the cmsc-style admission overview', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/admin/adm/admn')
        ->assertSuccessful()
        ->assertSee('ADM / Student Affairs')
        ->assertSee('ADM Dashboard')
        ->assertSee('Achievements')
        ->assertSee('ADMISSION INQUIRY')
        ->assertSee('REGISTRATION')
        ->assertSee('Quick Actions')
        ->assertSee('Add Student Registration')
        ->assertDontSee('Legacy table: students');
});

test('main dashboard keeps coordinator widget links functional', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/admin/admin/dashboard')
        ->assertSuccessful()
        ->assertSee('ADMISSION INQUIRY')
        ->assertSee('/admin/adm/enquiry')
        ->assertSee('/admin/adm/student_regd')
        ->assertSee('/admin/hrms/staff')
        ->assertSee('/admin/account/purchases')
        ->assertSee('/admin/account/sales');
});
