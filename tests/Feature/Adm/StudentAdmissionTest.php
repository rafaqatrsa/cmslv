<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

beforeEach(function (): void {
    $this->withoutVite();
});

test('student admission routes are registered separately from student registration', function (): void {
    expect(Route::has('admin.adm.student-admissions.create'))->toBeTrue()
        ->and(Route::has('admin.adm.student-admissions.store'))->toBeTrue()
        ->and(Route::has('admin.adm.student-admissions.edit'))->toBeTrue()
        ->and(Route::has('admin.adm.student-admissions.update'))->toBeTrue()
        ->and(Route::has('admin.adm.student-admissions.destroy'))->toBeTrue()
        ->and(Route::has('admin.adm.student-admissions.class-sections'))->toBeTrue()
        ->and(Route::has('admin.adm.student-admissions.registration-detail'))->toBeTrue();
});

test('an authenticated user can open the student admission form', function (): void {
    $this->actingAs(User::factory()->create())
        ->get(route('admin.adm.student-admissions.create'))
        ->assertSuccessful()
        ->assertSee('Student Admission')
        ->assertSee('Parent / Guardian Information')
        ->assertSee('Admission Fees')
        ->assertSee('Dates and Documents');
});

test('student admission validation rejects an incomplete submission', function (): void {
    $response = $this->actingAs(User::factory()->create())
        ->post(route('admin.adm.student-admissions.store'), []);

    $response->assertSessionHasErrors([
        'admission_no',
        'session_id',
        'adcademicyear_id',
        'class_id',
        'section_id',
        'firstname',
        'dob',
        'gender',
        'father_name',
        'father_phone',
        'father_cnic',
        'guardian_name',
        'guardian_phone',
    ]);
});
