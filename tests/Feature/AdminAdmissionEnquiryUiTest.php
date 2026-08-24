<?php

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    $this->withoutVite();

    foreach (['follow_up', 'enquiry', 'branch', 'classes', 'source', 'reference', 'occupation', 'staff'] as $table) {
        Schema::dropIfExists($table);
    }

    Schema::create('branch', function (Blueprint $table): void {
        $table->increments('id');
        $table->string('name');
        $table->integer('is_active')->default(1);
        $table->timestamp('created_at')->nullable();
    });

    Schema::create('classes', function (Blueprint $table): void {
        $table->increments('id');
        $table->string('class');
    });

    Schema::create('source', function (Blueprint $table): void {
        $table->increments('id');
        $table->string('source');
    });

    Schema::create('reference', function (Blueprint $table): void {
        $table->increments('id');
        $table->string('reference');
    });

    Schema::create('occupation', function (Blueprint $table): void {
        $table->increments('id');
        $table->string('name');
    });

    Schema::create('staff', function (Blueprint $table): void {
        $table->increments('id');
        $table->string('name');
        $table->timestamp('created_at')->nullable();
    });

    Schema::create('enquiry', function (Blueprint $table): void {
        $table->increments('id');
        $table->integer('brc_id')->nullable();
        $table->string('enquiry_no')->nullable();
        $table->string('name')->nullable();
        $table->string('contact')->nullable();
        $table->string('phone')->nullable();
        $table->string('guardian_relation')->nullable();
        $table->string('reference')->nullable();
        $table->date('date')->nullable();
        $table->string('source')->nullable();
        $table->string('status')->nullable();
        $table->date('follow_up_date')->nullable();
        $table->text('description')->nullable();
        $table->text('note')->nullable();
        $table->integer('created_by')->nullable();
        $table->integer('updated_by')->nullable();
        $table->timestamp('created_at')->nullable();
        $table->timestamp('updated_at')->nullable();
    });

    Schema::create('follow_up', function (Blueprint $table): void {
        $table->increments('id');
        $table->integer('enquiry_id');
        $table->date('date')->nullable();
        $table->date('next_date')->nullable();
        $table->text('response')->nullable();
        $table->text('note')->nullable();
        $table->integer('followup_by')->nullable();
        $table->timestamp('created_at')->nullable();
        $table->timestamp('updated_at')->nullable();
    });

    DB::table('branch')->insert([
        'id' => 1,
        'name' => 'Main Campus',
        'is_active' => 1,
        'created_at' => now(),
    ]);

    DB::table('classes')->insert([
        'id' => 1,
        'class' => 'Class 10',
    ]);

    DB::table('source')->insert([
        'id' => 1,
        'source' => 'Facebook',
    ]);

    DB::table('staff')->insert([
        'id' => 1,
        'name' => 'Mehwish (stf-01)',
        'created_at' => now(),
    ]);

    DB::table('enquiry')->insert([
        'id' => 1,
        'brc_id' => 1,
        'enquiry_no' => 'ENQ-001',
        'name' => 'Rafaqat Ali',
        'contact' => '923436050016',
        'phone' => '923436050016',
        'guardian_relation' => 'Father',
        'reference' => 'Family',
        'date' => '2026-07-03',
        'source' => 'Facebook',
        'status' => 'Active',
        'follow_up_date' => '2026-07-08',
        'description' => 'Student enquiry',
        'note' => 'Student enquiry',
        'created_by' => 1,
        'updated_by' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
});

test('admission enquiry page renders a cmsc-style layout from database records', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.adm.enquiries.index'))
        ->assertSuccessful()
        ->assertSee('Front Office')
        ->assertSee('Select Criteria')
        ->assertSee('Admission Enquiry')
        ->assertSee('Search...')
        ->assertSee('Add')
        ->assertSee('Admission Enquiry Form')
        ->assertSee('id="open-add-enquiry"', false)
        ->assertSee('id="add-enquiry-modal"', false)
        ->assertSee('id="admission-enquiry-modal-form"', false)
        ->assertSee('method="POST"', false)
        ->assertDontSee('href="/admin/adm/enquiry/create"', false)
        ->assertSee('data-enquiry-export="copy"', false)
        ->assertSee('data-enquiry-export="excel"', false)
        ->assertSee('data-enquiry-export="csv"', false)
        ->assertSee('data-enquiry-export="pdf"', false)
        ->assertSee('data-enquiry-export="print"', false)
        ->assertSee('data-enquiry-export="columns"', false)
        ->assertSee('enquiry-column-menu', false)
        ->assertSee('assets/fullcalendar/sweetalert2.js', false)
        ->assertSee('assets/dist/datatables/js/pdfmake.min.js', false)
        ->assertSee('data-enquiry-id="1"', false)
        ->assertSee('X-CSRF-TOKEN', false)
        ->assertSee('Main Campus')
        ->assertSee('Class 10')
        ->assertSee('Facebook')
        ->assertSee('Rafaqat Ali')
        ->assertSee('Father')
        ->assertSee('Mehwish (stf-01)')
        ->assertSee('0 times')
        ->assertSee('Active')
        ->assertDontSee('ADM / Student Affairs');
});

test('admission enquiry add form contains the coordinator fields', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('admin.adm.enquiries.create'))
        ->assertSuccessful()
        ->assertSee('Name of Visitor')
        ->assertSee('ID Card')
        ->assertSee('Visitor Relation')
        ->assertSee('Proposed Kids for Admission')
        ->assertSee('Father / Guardian Guidance')
        ->assertSee('How did you hear about us?')
        ->assertSee('Follow Up')
        ->assertSee('Fee Packages Policy')
        ->assertSee('Show Fees');
});

test('admission enquiry add form stores the enquiry', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('admin.adm.enquiries.store'), [
            'brc_id' => 1,
            'name' => 'New Visitor',
            'contact' => '3001234567',
            'source' => 'Facebook',
            'date' => '2026-08-21',
            'follow_up_date' => '2026-08-25',
            'email' => 'visitor@example.com',
            'father_name' => 'Guardian Name',
            'description' => 'Admission enquiry',
            'note' => 'Follow up required',
            'class_id' => [1],
            'kid_name' => ['Child Name'],
            'number_of_kids' => [1],
        ])
        ->assertSuccessful()
        ->assertJsonPath('redirect_url', route('admin.adm.enquiries.index', absolute: false));

    expect(DB::table('enquiry')->where('name', 'New Visitor')->exists())->toBeTrue();
});

test('admission enquiry data endpoint searches relevant columns without a page reload', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->getJson(route('admin.adm.enquiries.data', ['search' => 'Mehwish']))
        ->assertSuccessful()
        ->assertJsonPath('data.0.name', 'Rafaqat Ali')
        ->assertJsonPath('data.0.assigned_to', 'Mehwish (stf-01)')
        ->assertJsonPath('total', 1);

    $this->actingAs($user)
        ->getJson(route('admin.adm.enquiries.data', ['search' => 'Father']))
        ->assertSuccessful()
        ->assertJsonPath('data.0.relation', 'Father')
        ->assertJsonPath('total', 1);
});

test('admission enquiry row actions provide details, update status, and follow-up workflow', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->getJson(route('admin.adm.enquiries.details', ['id' => 1]))
        ->assertSuccessful()
        ->assertJsonPath('record.name', 'Rafaqat Ali');

    $this->actingAs($user)
        ->patchJson(route('admin.adm.enquiries.status', ['id' => 1]), ['status' => 'Active'])
        ->assertSuccessful()
        ->assertJsonPath('status', 'Active');

    $this->actingAs($user)
        ->post(route('admin.adm.enquiries.update', ['id' => 1]), [
            '_method' => 'PUT',
            'name' => 'Updated Visitor',
            'contact' => '3001234567',
            'source' => 'Facebook',
            'date' => '2026-08-21',
            'follow_up_date' => '2026-08-25',
        ])
        ->assertSuccessful()
        ->assertJsonPath('record.name', 'Updated Visitor');

    $this->actingAs($user)
        ->postJson(route('admin.adm.enquiries.follow-ups.store', ['id' => 1]), [
            'date' => '2026-08-21',
            'follow_up_date' => '2026-08-25',
            'response' => 'Called visitor',
            'note' => 'Interested',
        ])
        ->assertSuccessful()
        ->assertJsonPath('count', 1);

    expect(DB::table('follow_up')->where('enquiry_id', 1)->count())->toBe(1);
});

test('admission enquiry delete action removes the enquiry and related follow-ups', function () {
    $user = User::factory()->create();
    DB::table('follow_up')->insert(['enquiry_id' => 1, 'response' => 'Existing follow-up']);

    $this->actingAs($user)
        ->deleteJson(route('admin.adm.enquiries.destroy', ['id' => 1]))
        ->assertSuccessful()
        ->assertJsonPath('message', 'Admission enquiry deleted successfully.');

    expect(DB::table('enquiry')->where('id', 1)->exists())->toBeFalse();
    expect(DB::table('follow_up')->where('enquiry_id', 1)->exists())->toBeFalse();
});

test('admission enquiry supports duplicate phone checks and inline master creation', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('admin.adm.enquiries.check-number'), ['phone_number' => '923436050016'])
        ->assertSuccessful()
        ->assertJsonPath('status', 'success')
        ->assertJsonPath('name', 'Rafaqat Ali');

    $this->actingAs($user)
        ->postJson(route('admin.adm.enquiries.source.store'), ['name' => 'Walk In'])
        ->assertSuccessful()
        ->assertJsonPath('data.name', 'Walk In');

    $this->actingAs($user)
        ->postJson(route('admin.adm.enquiries.source.store'), ['name' => 'walk in'])
        ->assertStatus(422)
        ->assertJsonPath('error.name', 'Record already exists');
});
