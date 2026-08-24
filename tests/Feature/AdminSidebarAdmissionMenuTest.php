<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    $this->withoutVite();

    Schema::dropIfExists('permission_category');

    Schema::create('permission_category', function (Blueprint $table): void {
        $table->increments('id');
        $table->integer('perm_group_id')->nullable();
        $table->string('name')->nullable();
        $table->string('short_code')->nullable();
        $table->integer('enable_view')->default(1);
        $table->integer('enable_add')->default(0);
        $table->integer('enable_edit')->default(0);
        $table->integer('enable_delete')->default(0);
        $table->integer('enable_branch')->default(0);
        $table->timestamp('created_at')->nullable();
    });

    collect([
        ['name' => 'Admission Enquiry', 'short_code' => 'admission_enquiry'],
        ['name' => 'Student Regd', 'short_code' => 'student_regd'],
        ['name' => 'Student', 'short_code' => 'student'],
        ['name' => 'Students Directory', 'short_code' => 'students_directory'],
        ['name' => 'Siblings Directory', 'short_code' => 'siblings_directory'],
        ['name' => 'Transfer Class/Section', 'short_code' => 'transfer_class_section'],
        ['name' => 'Promote Students', 'short_code' => 'promote_student'],
    ])->each(function (array $item): void {
        DB::table('permission_category')->insert([
            'perm_group_id' => 18,
            'name' => $item['name'],
            'short_code' => $item['short_code'],
            'enable_view' => 1,
            'enable_add' => 1,
            'enable_edit' => 1,
            'enable_delete' => 1,
            'enable_branch' => 1,
            'created_at' => now(),
        ]);
    });
});

test('admission submenu is rendered from permission category rows', function () {
    $html = view('admin.partials.sidebar')->render();

    expect($html)->toContain('Admission Process');
    expect($html)->toContain('Admission Enquiry');
    expect($html)->toContain('Student Registration');
    expect($html)->toContain('Student Admission');
    expect($html)->toContain('Students Directory');
    expect($html)->toContain('Siblings Directory');
    expect($html)->toContain('Transfer/Class-section');
    expect($html)->toContain('Promote Students');
});
