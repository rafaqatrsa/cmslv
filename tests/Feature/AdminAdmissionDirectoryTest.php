<?php

use App\Models\User;

beforeEach(function () {
    $this->withoutVite();
});

test('student directory page renders cmsc-style criteria and blank state', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.adm.students.index'))
        ->assertSuccessful()
        ->assertSee('ADM Dashboard')
        ->assertSee('Students Directory')
        ->assertSee('Select Criteria')
        ->assertSee('Search By Keyword');
});

test('student directory exposes coordinator search controls and actions', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.adm.students.search', ['class_id' => 1]))
        ->assertSuccessful()
        ->assertSee('List View')
        ->assertSee('Details View')
        ->assertSee('Column visibility')
        ->assertSee('Student Details');
});

test('student directory section endpoint returns json', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->getJson(route('admin.adm.students.sections', ['class_id' => 1]))
        ->assertSuccessful()
        ->assertJsonStructure([]);
});

test('student transfer page renders cmsc-style criteria and blank state', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.adm.student-transfers.index'))
        ->assertSuccessful()
        ->assertSee('ADM Dashboard')
        ->assertSee('Transfer/Class-section')
        ->assertSee('Select Criteria');
});

test('student transfer rejects an empty selection before any database write', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('admin.adm.student-transfers.transfer'), [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['check', 'session_id', 'class_transfer_id', 'section_transfer_id', 'fee_transfer_mode']);
});
