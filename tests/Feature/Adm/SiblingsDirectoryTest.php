<?php

use App\Models\User;

beforeEach(function () {
    $this->withoutVite();
});

it('renders the coordinator-style siblings directory', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.adm.siblings.index'))
        ->assertSuccessful()
        ->assertSee('Sibling List')
        ->assertSee('Add Sibling')
        ->assertSee('Sibling Information')
        ->assertSee('Associate Students')
        ->assertSee('assets/js/siblings-directory.js');
});

it('exposes sibling create, update and delete routes', function () {
    expect(route('admin.adm.siblings.store'))->toContain('/admin/adm/siblings')
        ->and(route('admin.adm.siblings.update', 1))->toContain('/admin/adm/siblings/1')
        ->and(route('admin.adm.siblings.destroy', 1))->toContain('/admin/adm/siblings/1');
});
