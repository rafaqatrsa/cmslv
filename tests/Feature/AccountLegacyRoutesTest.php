<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    $this->withoutVite();
});

it('requires authentication for account legacy urls', function () {
    $this->get('/admin/account/accounts')->assertRedirect('/login');
});

it('renders every account legacy url for authenticated admin users', function (string $uri) {
    $user = User::factory()->create();

    $this->actingAs($user)->get($uri)->assertSuccessful();
})->with([
    '/admin/account/accounts',
    '/admin/account/accounts/dashboard',
    '/admin/account/accounts/newaccounts',
    '/admin/account/accounts/accountshead',
    '/admin/account/brands',
    '/admin/account/classbooksets',
    '/admin/account/contra',
    '/admin/account/documentsacc',
    '/admin/account/expenses',
    '/admin/account/feemaster',
    '/admin/account/invoicebooksets',
    '/admin/account/invoicebooksetreturns',
    '/admin/account/itemcategory',
    '/admin/account/journalvoucher',
    '/admin/account/payments',
    '/admin/account/payroll',
    '/admin/account/products',
    '/admin/account/producttype',
    '/admin/account/purchases',
    '/admin/account/purchasereturns',
    '/admin/account/receipts',
    '/admin/account/royalty',
    '/admin/account/sales',
    '/admin/account/salesreturns',
    '/admin/account/stock',
    '/admin/account/studentfee',
    '/admin/account/supplier',
    '/admin/account/units',
]);

it('registers the required account route names', function (string $routeName) {
    expect(Route::has($routeName))->toBeTrue();
})->with([
    'admin.account.accounts.index',
    'admin.account.accounts.dashboard.legacy',
    'admin.account.accounts.newaccounts',
    'admin.account.accounts.newaccounts.store',
    'admin.account.accounts.newaccounts.edit',
    'admin.account.accounts.newaccounts.update',
    'admin.account.accounts.accountshead',
    'admin.account.accounts.accountshead.store',
    'admin.account.accounts.accountshead.edit',
    'admin.account.accounts.accountshead.update',
    'admin.account.accounts.newaccounts.by-head',
    'admin.account.accounts.change-status',
    'admin.account.accounts.change-status-post',
    'admin.account.brands.index',
    'admin.account.class-book-sets.index',
    'admin.account.contra.index',
    'admin.account.documents.index',
    'admin.account.expenses.index',
    'admin.account.fee-master.index',
    'admin.account.invoice-book-sets.index',
    'admin.account.invoice-book-set-returns.index',
    'admin.account.item-categories.index',
    'admin.account.journal-vouchers.index',
    'admin.account.payments.index',
    'admin.account.payroll.index',
    'admin.account.products.index',
    'admin.account.product-types.index',
    'admin.account.purchases.index',
    'admin.account.purchase-returns.index',
    'admin.account.receipts.index',
    'admin.account.royalty.index',
    'admin.account.sales.index',
    'admin.account.sales-returns.index',
    'admin.account.stock.index',
    'admin.account.student-fees.index',
    'admin.account.suppliers.index',
    'admin.account.units.index',
]);

it('renders the Coordinator-style account dashboard for the legacy dashboard url', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/admin/account/accounts/dashboard')
        ->assertSuccessful()
        ->assertSee('Fees Collection Statistics For', false)
        ->assertSee('Fee Overview', false)
        ->assertSee('Expenses For', false)
        ->assertSee('Chart Of Accounts', false)
        ->assertSee('Accounting Records', false)
        ->assertSee('Network Associate Account', false);
});

it('opens only one account sidebar section at a time', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get('/admin/account/accounts')
        ->assertSuccessful();

    expect(substr_count($response->getContent(), 'admin-sidebar-tree is-open'))->toBe(1)
        ->and($response->getContent())->toContain('data-sidebar-toggle')
        ->and($response->getContent())->toContain('transition: max-height .24s ease');
});

it('renders the three Coordinator chart of accounts screens', function (string $uri, string $expectedText) {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get($uri)
        ->assertSuccessful()
        ->assertSee($expectedText, false)
        ->assertSee('Chart Of Accounts', false)
        ->assertDontSee('Student Information', false);
})->with([
    ['/admin/account/accounts/newaccounts', 'Accounts Type List'],
    ['/admin/account/accounts/accountshead', 'Accounts Head List'],
    ['/admin/account/accounts', 'List View'],
]);
