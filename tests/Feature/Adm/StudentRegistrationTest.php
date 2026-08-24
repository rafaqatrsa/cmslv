<?php

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    $this->withoutVite();
    ensureAdmissionTables();
});

test('student registration routes are registered', function () {
    expect(Route::has('admin.adm.student-registrations.index'))->toBeTrue()
        ->and(Route::has('admin.adm.student-registrations.create'))->toBeTrue()
        ->and(Route::has('admin.adm.student-registrations.store'))->toBeTrue()
        ->and(Route::has('admin.adm.student-registrations.show'))->toBeTrue()
        ->and(Route::has('admin.adm.student-registrations.edit'))->toBeTrue()
        ->and(Route::has('admin.adm.student-registrations.update'))->toBeTrue()
        ->and(Route::has('admin.adm.student-registrations.destroy'))->toBeTrue()
        ->and(Route::has('admin.adm.student-registrations.enquiry-detail'))->toBeTrue()
        ->and(Route::has('admin.adm.student-registrations.location-options'))->toBeTrue();
});

test('authenticated users can view the student registration page', function () {
    $user = User::factory()->create();

    DB::table('classes')->updateOrInsert(['id' => 9001], [
        'class' => 'One',
        'is_active' => 'yes',
        'created_at' => now(),
        'updated_at' => now()->toDateString(),
    ]);

    DB::table('sessions')->updateOrInsert(['id' => 9001], [
        'session' => '2026-27',
        'start_date' => now()->toDateString(),
        'end_date' => now()->addYear()->toDateString(),
        'is_active' => 'yes',
        'created_at' => now(),
        'updated_at' => now()->toDateString(),
    ]);

    $this->actingAs($user)
        ->get(route('admin.adm.student-registrations.index'))
        ->assertSuccessful()
        ->assertDontSee('ADM / Student Affairs')
        ->assertSee('Student Registration')
        ->assertSee('Add Student Registration')
        ->assertSee('Admission Enquiry Information')
        ->assertSee('Student Information')
        ->assertSee('Parent / Guardian Information')
        ->assertSee('Fee Information')
        ->assertSee('Pervious School')
        ->assertSee('Student Photo');
});

test('authenticated users can store a student registration', function () {
    $user = User::factory()->create();

    DB::table('classes')->updateOrInsert(['id' => 9002], [
        'class' => 'Two',
        'is_active' => 'yes',
        'created_at' => now(),
        'updated_at' => now()->toDateString(),
    ]);

    DB::table('sessions')->updateOrInsert(['id' => 9002], [
        'session' => '2026-27',
        'start_date' => now()->toDateString(),
        'end_date' => now()->addYear()->toDateString(),
        'is_active' => 'yes',
        'created_at' => now(),
        'updated_at' => now()->toDateString(),
    ]);

    DB::table('adcademicyear')->updateOrInsert(['id' => 9002], [
        'name' => '2026',
        'is_active' => 'yes',
        'created_at' => now(),
        'updated_at' => now()->toDateString(),
    ]);

    $response = $this->actingAs($user)->post(route('admin.adm.student-registrations.store'), [
        'regd_no' => 'REG-9002',
        'class_id' => 9002,
        'session_id' => 9002,
        'adcademicyear_id' => 9002,
        'regd_date' => now()->toDateString(),
        'dob' => now()->subYears(5)->toDateString(),
        'student_name' => 'Ayaan Khan',
        'b_form_no' => '12345-6789012-3',
        'gender' => 'Male',
        'father_name' => 'Ayaan Father',
        'father_phone' => '3000000000',
        'father_cnic' => '12345-1234567-1',
        'guardian_is' => 'father',
        'guardian_name' => 'Ayaan Father',
        'guardian_relation' => 'Father',
        'guardian_phone' => '3000000000',
        'class_left' => 'Prep',
        'leaving_date' => now()->subYear()->toDateString(),
        'fee_rows' => [],
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('students_regd', [
        'firstname' => 'Ayaan',
        'lastname' => 'Khan',
        'class_id' => 9002,
        'session_id' => 9002,
        'gender' => 'Male',
        'bayformno' => '12345-6789012-3',
        'previous_class' => 'Prep',
        'pervious_schl_leaving_date' => now()->subYear()->startOfDay()->toDateTimeString(),
    ]);
});

function ensureAdmissionTables(): void
{
    if (! Schema::hasTable('classes')) {
        Schema::create('classes', function (Blueprint $table): void {
            $table->id();
            $table->string('class');
            $table->string('is_active')->nullable();
            $table->timestamps();
        });
    }

    if (Schema::hasTable('sessions') && ! Schema::hasColumn('sessions', 'session')) {
        Schema::drop('sessions');
    }

    if (! Schema::hasTable('sessions')) {
        Schema::create('sessions', function (Blueprint $table): void {
            $table->id();
            $table->string('session');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('is_active')->nullable();
            $table->timestamps();
        });
    }

    if (! Schema::hasTable('adcademicyear')) {
        Schema::create('adcademicyear', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('is_active')->nullable();
            $table->timestamps();
        });
    }

    if (! Schema::hasTable('students_regd')) {
        Schema::create('students_regd', function (Blueprint $table): void {
            $table->id();
            $table->integer('brc_id')->nullable();
            $table->string('regd_no')->nullable();
            $table->integer('class_id')->nullable();
            $table->integer('session_id')->nullable();
            $table->integer('adcademicyear_id')->nullable();
            $table->date('regd_date')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->text('image')->nullable();
            $table->string('mobile_country_code', 5)->nullable();
            $table->string('mobileno')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->integer('religion')->nullable();
            $table->integer('medium_id')->nullable();
            $table->integer('previous_school_id')->nullable();
            $table->string('previous_class')->nullable();
            $table->date('pervious_schl_leaving_date')->nullable();
            $table->string('bayformno')->nullable();
            $table->integer('district_id')->nullable();
            $table->integer('tehsils_id')->nullable();
            $table->integer('area_id')->nullable();
            $table->string('father_name')->nullable();
            $table->string('father_country_code', 5)->nullable();
            $table->string('father_phone')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_country_code')->nullable();
            $table->string('mother_phone')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('father_cnic')->nullable();
            $table->string('guardian_is')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_relation')->nullable();
            $table->string('guardian_phone')->nullable();
            $table->string('guardian_country_code', 5)->nullable();
            $table->string('guardian_occupation')->nullable();
            $table->string('guardian_email')->nullable();
            $table->string('address')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('due_date')->nullable();
            $table->date('regd_date_current')->nullable();
            $table->string('is_active')->nullable();
            $table->integer('regd_status')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->integer('registration_enquiry_id')->nullable();
            $table->integer('registration_enquiry_kid_id')->nullable();
            $table->timestamps();
        });
    }

    if (! Schema::hasTable('student_regd_fee')) {
        Schema::create('student_regd_fee', function (Blueprint $table): void {
            $table->id();
            $table->integer('student_regd_id');
            $table->integer('brc_id')->nullable();
            $table->integer('class_id')->nullable();
            $table->integer('session_id')->nullable();
            $table->integer('feetype_id')->nullable();
            $table->string('frequency')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->date('date')->nullable();
            $table->string('note', 200)->nullable();
            $table->string('is_active')->nullable();
            $table->timestamps();
        });
    }
}
