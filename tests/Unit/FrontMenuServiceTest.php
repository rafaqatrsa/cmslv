<?php

use App\Services\Front\MenuService;
use Illuminate\Validation\ValidationException;

uses(Tests\TestCase::class);

it('rejects a menu item becoming its own parent', function () {
    app(MenuService::class)->assertNotSelfParent(10, 10);
})->throws(ValidationException::class);

it('allows a different parent menu item', function () {
    app(MenuService::class)->assertNotSelfParent(10, 11);

    expect(true)->toBeTrue();
});
