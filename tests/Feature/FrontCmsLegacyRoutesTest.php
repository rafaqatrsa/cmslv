<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    $this->withoutVite();
});

it('requires authentication for front cms legacy urls', function () {
    $this->get('/admin/front/page')->assertRedirect('/login');
});

it('renders every front cms legacy url for authenticated admin users', function (string $uri) {
    $user = User::factory()->create();

    $this->actingAs($user)->get($uri)->assertSuccessful();
})->with([
    '/admin/front/banner',
    '/admin/front/events',
    '/admin/front/gallery',
    '/admin/front/media',
    '/admin/front/menus',
    '/admin/front/notice',
    '/admin/front/page',
]);

it('registers the required front cms route names', function (string $routeName) {
    expect(Route::has($routeName))->toBeTrue();
})->with([
    'admin.front.banners.index',
    'admin.front.events.index',
    'admin.front.galleries.index',
    'admin.front.media.index',
    'admin.front.menus.index',
    'admin.front.notices.index',
    'admin.front.pages.index',
]);
