<?php

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    $this->withoutVite();

    foreach (['student_session', 'students', 'sessions', 'classes', 'sections'] as $table) {
        Schema::dropIfExists($table);
    }

    Schema::create('sessions', function (Blueprint $table): void {
        $table->increments('id');
        $table->string('session');
    });

    Schema::create('classes', function (Blueprint $table): void {
        $table->increments('id');
        $table->string('class');
    });

    Schema::create('sections', function (Blueprint $table): void {
        $table->increments('id');
        $table->string('section');
    });

    Schema::create('students', function (Blueprint $table): void {
        $table->increments('id');
        $table->string('admission_no')->nullable();
        $table->string('firstname')->nullable();
        $table->string('lastname')->nullable();
        $table->string('mobileno')->nullable();
        $table->timestamp('created_at')->nullable();
        $table->date('updated_at')->nullable();
    });

    Schema::create('student_session', function (Blueprint $table): void {
        $table->increments('id');
        $table->integer('brc_id')->nullable();
        $table->integer('session_id');
        $table->integer('student_id');
        $table->integer('student_sibling_id')->nullable();
        $table->integer('class_id');
        $table->integer('section_id')->nullable();
        $table->date('session_date')->nullable();
        $table->string('is_active')->nullable();
        $table->integer('is_alumni')->nullable();
        $table->string('fee_mode', 20)->nullable();
        $table->integer('created_by')->nullable();
        $table->integer('updated_by')->nullable();
        $table->timestamp('created_at')->nullable();
        $table->date('updated_at')->nullable();
    });

    DB::table('sessions')->insert([
        ['id' => 1, 'session' => '2025-26'],
        ['id' => 2, 'session' => '2026-27'],
    ]);

    DB::table('classes')->insert([
        ['id' => 1, 'class' => 'Class 9'],
        ['id' => 2, 'class' => 'Class 10'],
    ]);

    DB::table('sections')->insert([
        ['id' => 1, 'section' => 'A'],
    ]);

    DB::table('students')->insert([
        'id' => 1,
        'admission_no' => 'ADM-001',
        'firstname' => 'Hamza',
        'lastname' => 'Khan',
        'mobileno' => '3336453570',
        'created_at' => now(),
        'updated_at' => now()->toDateString(),
    ]);

    DB::table('student_session')->insert([
        'id' => 1,
        'brc_id' => 1,
        'session_id' => 1,
        'student_id' => 1,
        'student_sibling_id' => null,
        'class_id' => 1,
        'section_id' => 1,
        'session_date' => '2025-07-01',
        'is_active' => 'yes',
        'is_alumni' => 0,
        'fee_mode' => 'monthly',
        'created_by' => 1,
        'updated_by' => 1,
        'created_at' => now(),
        'updated_at' => now()->toDateString(),
    ]);
});

test('admission promotion page renders cmsc-style controls', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.adm.student-promotions.index', [
            'source_session_id' => 1,
            'source_class_id' => 1,
            'source_section_id' => 1,
            'target_session_id' => 2,
            'target_class_id' => 2,
            'target_section_id' => 1,
            'search' => 'Hamza',
        ]))
        ->assertSuccessful()
        ->assertSee('ADM Dashboard')
        ->assertSee('Promote Students')
        ->assertSee('Select Criteria')
        ->assertSee('Hamza Khan')
        ->assertSee('ADM-001')
        ->assertSee('2025-26')
        ->assertSee('Class 9')
        ->assertSee('A');
});

test('admission promotion creates a new student session in the target class and session', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('admin.adm.student-promotions.store'), [
            'source_session_id' => 1,
            'source_class_id' => 1,
            'source_section_id' => 1,
            'target_session_id' => 2,
            'target_class_id' => 2,
            'target_section_id' => 1,
            'search' => 'Hamza',
        ])
        ->assertRedirect(route('admin.adm.student-promotions.index', [
            'source_session_id' => 1,
            'source_class_id' => 1,
            'source_section_id' => 1,
            'target_session_id' => 2,
            'target_class_id' => 2,
            'target_section_id' => 1,
            'search' => 'Hamza',
        ]))
        ->assertSessionHas('status', '1 student(s) promoted successfully.');

    expect(DB::table('student_session')->count())->toBe(2);

    $this->assertDatabaseHas('student_session', [
        'student_id' => 1,
        'session_id' => 2,
        'class_id' => 2,
        'section_id' => 1,
    ]);
});
