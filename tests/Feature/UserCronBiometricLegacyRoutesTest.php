<?php

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    $this->withoutVite();

    if (! Schema::hasColumn('users', 'username')) {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('username')->nullable();
            $table->string('role')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('sibling_id')->nullable();
            $table->text('childs')->nullable();
            $table->unsignedBigInteger('brc_id')->nullable();
        });
    }
});

it('requires authentication for user portal legacy urls', function () {
    $this->get('/user/user/dashboard')->assertRedirect('/login');
});

it('renders every user portal legacy url for authenticated student users', function (string $uri) {
    $user = User::factory()->create();
    $user->forceFill([
        'username' => 'std'.$user->id,
        'role' => 'student',
        'user_id' => $user->id,
    ])->save();

    $this->actingAs($user, 'site')->get($uri)->assertSuccessful();
})->with([
    '/user/user/dashboard',
    '/user/user/profile',
    '/user/user/getfees',
    '/user/user/changepass',
    '/user/user/changeusername',
    '/user/apply_leave',
    '/user/attendence',
    '/user/book',
    '/user/conference',
    '/user/content',
    '/user/gmeet',
    '/user/video_tutorial',
]);

it('registers user cron and biometric route names', function (string $routeName) {
    expect(Route::has($routeName))->toBeTrue();
})->with([
    'user.dashboard',
    'user.profile.show',
    'user.fees.index',
    'user.password.edit',
    'user.password.update',
    'user.username.edit',
    'user.username.update',
    'user.leave-requests.index',
    'user.attendance.index',
    'user.books.index',
    'user.conferences.index',
    'user.content.index',
    'user.google-meet.index',
    'user.video-tutorials.index',
    'cron.index',
    'cron.biometric-attendance',
    'cron.student-attendance',
    'cron.auto-backup',
    'cron.fee-reminder',
    'cron.event-reminder',
    'cron.scheduled-sms-emails',
    'biometric.index',
    'biometric.get-user',
]);

it('updates a student portal password with the current password', function () {
    $user = User::factory()->create([
        'password' => Hash::make('old-password'),
    ]);
    $user->forceFill([
        'username' => 'std'.$user->id,
        'role' => 'student',
        'user_id' => $user->id,
    ])->save();

    $this->actingAs($user, 'site')
        ->post('/user/user/changepass', [
            'current_password' => 'old-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])
        ->assertRedirect(route('user.password.edit'));

    expect(Hash::check('new-password', $user->refresh()->password))->toBeTrue();
});

it('rejects invalid cron keys and accepts configured cron keys', function () {
    Config::set('cron.key', 'secret-key');

    $this->get('/cron/wrong-key')->assertNotFound();
    $this->get('/cron/secret-key')->assertOk()->assertJsonPath('job', 'index');
});

it('protects biometric endpoints when a biometric key is configured', function () {
    Config::set('biometric.key', 'device-secret');

    $this->get('/biometric/getUser')->assertForbidden();
    $this->get('/biometric/getUser?key=device-secret')->assertOk()->assertJsonStructure(['users']);
});
