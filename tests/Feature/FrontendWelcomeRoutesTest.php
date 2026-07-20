<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Schema::create('branch', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name')->nullable();
        $table->date('regd_date')->nullable();
        $table->integer('is_royalty_type')->nullable();
        $table->integer('is_royalty_amount')->nullable();
        $table->string('websiteurl')->nullable();
        $table->integer('country_id')->nullable();
        $table->integer('province_id')->nullable();
        $table->integer('division_id')->nullable();
        $table->integer('district_id')->nullable();
        $table->integer('tehsils_id')->nullable();
        $table->integer('area_id')->nullable();
        $table->integer('is_parent')->default(0);
        $table->integer('is_active')->default(1);
        $table->timestamps();
    });

    Schema::create('front_cms_pages', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('brc_id')->nullable();
        $table->string('page_type')->default('manual');
        $table->integer('is_homepage')->default(0);
        $table->string('title')->nullable();
        $table->string('url')->nullable();
        $table->string('type')->nullable();
        $table->string('slug')->nullable();
        $table->mediumText('meta_title')->nullable();
        $table->mediumText('meta_description')->nullable();
        $table->mediumText('meta_keyword')->nullable();
        $table->text('name')->nullable();
        $table->text('designation')->nullable();
        $table->string('feature_image')->nullable();
        $table->longText('description')->nullable();
        $table->text('photo')->nullable();
        $table->text('sign')->nullable();
        $table->date('publish_date')->nullable();
        $table->integer('publish')->default(1);
        $table->integer('sidebar')->default(0);
        $table->string('is_active')->default('yes');
        $table->timestamp('created_at')->nullable();
    });

    Schema::create('front_cms_programs', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('brc_id')->nullable();
        $table->string('type')->nullable();
        $table->string('slug')->nullable();
        $table->mediumText('url')->nullable();
        $table->string('title')->nullable();
        $table->date('date')->nullable();
        $table->date('event_start')->nullable();
        $table->date('event_end')->nullable();
        $table->mediumText('event_venue')->nullable();
        $table->mediumText('description')->nullable();
        $table->string('is_active')->default('yes');
        $table->mediumText('meta_title')->nullable();
        $table->mediumText('meta_description')->nullable();
        $table->mediumText('meta_keyword')->nullable();
        $table->mediumText('feature_image')->nullable();
        $table->string('image')->nullable();
        $table->date('publish_date')->nullable();
        $table->string('publish')->default('1');
        $table->integer('sidebar')->default(0);
        $table->timestamp('created_at')->nullable();
    });

    Schema::create('front_cms_media_gallery', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('brc_id')->nullable();
        $table->string('image')->nullable();
        $table->string('thumb_path')->nullable();
        $table->string('dir_path')->nullable();
        $table->string('img_name')->nullable();
        $table->string('thumb_name')->nullable();
        $table->string('file_type')->nullable();
        $table->string('file_size')->nullable();
        $table->mediumText('vid_url')->nullable();
        $table->string('vid_title')->nullable();
        $table->timestamp('created_at')->nullable();
    });

    Schema::create('front_cms_settings', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('brc_id')->nullable();
        $table->string('logo')->nullable();
        $table->string('footer_text')->nullable();
        $table->timestamp('created_at')->nullable();
    });

    Schema::create('system_settings', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('brc_id')->nullable();
        $table->string('name')->nullable();
        $table->string('email')->nullable();
        $table->string('phone')->nullable();
        $table->text('address')->nullable();
        $table->integer('session_id')->nullable();
        $table->date('regd_date')->nullable();
        $table->integer('maintenance_mode')->nullable();
        $table->timestamp('created_at')->nullable();
        $table->date('updated_at')->nullable();
    });

    Schema::create('enquiry', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('brc_id')->nullable();
        $table->string('enquiry_no')->nullable();
        $table->string('name');
        $table->string('contact');
        $table->string('country_code')->nullable();
        $table->string('email')->nullable();
        $table->string('father_name')->nullable();
        $table->text('address');
        $table->string('phone')->nullable();
        $table->string('whatsapp')->nullable();
        $table->string('reference');
        $table->date('date');
        $table->string('description', 500);
        $table->date('follow_up_date');
        $table->text('note');
        $table->string('source');
        $table->string('status');
        $table->integer('created_by');
        $table->integer('updated_by')->nullable();
        $table->timestamps();
    });
});

it('renders the frontend static pages', function (string $uri) {
    $this->get($uri)->assertSuccessful();
})->with([
    '/',
    '/frontend',
    '/franchises',
    '/franchiseoffer',
    '/register',
    '/privacypolicy',
    '/contactus',
]);

it('renders branch, page, and read routes from legacy urls', function () {
    $branchId = DB::table('branch')->insertGetId([
        'name' => 'Main',
        'websiteurl' => 'main',
        'is_parent' => 0,
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('front_cms_pages')->insert([
        'brc_id' => $branchId,
        'title' => 'About',
        'slug' => 'about',
        'description' => 'About page',
        'publish' => 1,
        'is_active' => 'yes',
        'created_at' => now(),
    ]);

    DB::table('front_cms_programs')->insert([
        'brc_id' => $branchId,
        'title' => 'News',
        'slug' => 'news',
        'description' => 'News post',
        'publish' => '1',
        'is_active' => 'yes',
        'created_at' => now(),
    ]);

    $this->get('/branch/'.$branchId)->assertSuccessful();
    $this->get('/page/main/about')->assertSuccessful();
    $this->get('/read/main/news')->assertSuccessful();
});

it('returns 404 for missing dynamic content', function () {
    $this->get('/branch/999')->assertNotFound();
    $this->get('/page/missing/page')->assertNotFound();
    $this->get('/read/missing/post')->assertNotFound();
});

it('stores registration submissions as enquiries', function () {
    $this->post('/register', [
        'name' => 'Student One',
        'contact' => '03000000000',
        'email' => 'student@example.com',
        'address' => 'Test address',
        'description' => 'Registration details',
    ])->assertRedirect(route('frontend.register', absolute: false));

    $this->assertDatabaseHas('enquiry', [
        'name' => 'Student One',
        'source' => 'online_registration',
    ]);
});

it('stores contact submissions as enquiries', function () {
    $this->post('/contactus', [
        'name' => 'Visitor One',
        'contact' => '03000000001',
        'email' => 'visitor@example.com',
        'description' => 'Please contact me.',
    ])->assertRedirect(route('frontend.contact-us', absolute: false));

    $this->assertDatabaseHas('enquiry', [
        'name' => 'Visitor One',
        'source' => 'website_contact',
    ]);
});
