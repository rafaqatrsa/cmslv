<?php

use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    $this->withoutVite();

    collect([
        'roles_permissions',
        'permission_category',
        'system_notification',
        'front_cms_media_gallery',
        'front_cms_programs',
        'front_cms_pages',
        'libarary_members',
        'staff',
        'roles',
        'branch',
    ])->each(fn (string $table) => Schema::dropIfExists($table));

    Schema::create('branch', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name')->nullable();
        $table->integer('is_active')->default(1);
        $table->timestamps();
    });

    Schema::create('roles', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name')->nullable();
        $table->integer('is_active')->default(1);
        $table->integer('is_system')->default(0);
        $table->integer('is_superadmin')->default(0);
        $table->timestamp('created_at')->nullable();
        $table->date('updated_at')->nullable();
    });

    Schema::create('staff', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('brc_id')->nullable();
        $table->integer('role_id')->nullable();
        $table->string('employee_id')->nullable();
        $table->string('name')->nullable();
        $table->string('surname')->nullable();
        $table->string('email')->nullable();
        $table->string('password')->nullable();
        $table->integer('user_id')->nullable();
        $table->integer('is_active')->default(1);
        $table->timestamps();
    });

    Schema::create('permission_category', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name')->nullable();
        $table->string('short_code')->nullable();
        $table->integer('enable_view')->default(1);
        $table->timestamp('created_at')->nullable();
    });

    Schema::create('roles_permissions', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('brc_id')->nullable();
        $table->integer('role_id')->nullable();
        $table->integer('perm_cat_id')->nullable();
        $table->integer('can_branch')->nullable();
        $table->integer('can_view')->default(1);
        $table->integer('can_add')->nullable();
        $table->integer('can_edit')->nullable();
        $table->integer('can_delete')->nullable();
        $table->timestamp('created_at')->nullable();
    });

    Schema::create('front_cms_pages', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('brc_id')->nullable();
        $table->string('title')->nullable();
        $table->string('slug')->nullable();
        $table->longText('description')->nullable();
        $table->integer('publish')->default(1);
        $table->string('is_active')->default('yes');
        $table->timestamp('created_at')->nullable();
    });

    Schema::create('front_cms_programs', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('brc_id')->nullable();
        $table->string('title')->nullable();
        $table->string('slug')->nullable();
        $table->mediumText('description')->nullable();
        $table->string('publish')->default('1');
        $table->string('is_active')->default('yes');
        $table->timestamp('created_at')->nullable();
    });

    Schema::create('front_cms_media_gallery', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('brc_id')->nullable();
        $table->string('image')->nullable();
        $table->timestamp('created_at')->nullable();
    });

    Schema::create('libarary_members', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('brc_id')->nullable();
        $table->string('library_card_no')->nullable();
        $table->string('member_type')->nullable();
        $table->integer('member_id')->nullable();
        $table->string('is_active')->default('yes');
        $table->timestamps();
    });

    Schema::create('system_notification', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('brc_id')->nullable();
        $table->integer('session_id')->nullable();
        $table->string('notification_title');
        $table->string('notification_type');
        $table->text('notification_desc')->nullable();
        $table->string('notification_for');
        $table->integer('role_id')->nullable();
        $table->integer('receiver_id')->nullable();
        $table->integer('receiver_other_id')->nullable();
        $table->dateTime('date');
        $table->string('is_active');
        $table->timestamp('created_at')->nullable();
    });
});

it('requires authentication for admin legacy urls', function () {
    $this->get('/admin/admin/dashboard')->assertRedirect('/login');
    $this->get('/cmsc/admin/admin/dashboard')->assertRedirect('/login');
});

it('renders admin legacy urls for authenticated admin users', function (string $uri) {
    $user = User::factory()->create();

    $this->actingAs($user)->get($uri)->assertSuccessful();
})->with([
    '/admin/admin/dashboard',
    '/cmsc/admin/admin/dashboard',
    '/admin/dashboard',
    '/admin/staff',
    '/admin/report',
    '/admin/frontcms',
    '/admin/membership',
    '/admin/qms',
    '/admin/systemnotification',
]);

it('allows active legacy superadmin staff when the role active flag is disabled', function () {
    DB::table('roles')->insert([
        'id' => 1,
        'name' => 'Super Admin',
        'is_active' => 0,
        'is_superadmin' => 1,
        'created_at' => now(),
    ]);

    $staff = Staff::query()->create([
        'name' => 'Super Admin',
        'email' => 'superadmin@example.com',
        'employee_id' => 'SA-001',
        'password' => Hash::make('password'),
        'role_id' => 1,
        'is_active' => 1,
    ]);

    $this->actingAs($staff)
        ->get('/admin/admin/dashboard')
        ->assertSuccessful();
});

it('filters system notifications using real legacy columns', function () {
    $user = User::factory()->create();

    DB::table('system_notification')->insert([
        'notification_title' => 'Fee reminder',
        'notification_type' => 'fees',
        'notification_desc' => 'Monthly fee reminder',
        'notification_for' => 'student',
        'date' => now(),
        'is_active' => 'yes',
        'created_at' => now(),
    ]);

    $this->actingAs($user)
        ->get('/admin/systemnotification?search=Fee&type=fees')
        ->assertSuccessful()
        ->assertSee('Fee reminder');
});
