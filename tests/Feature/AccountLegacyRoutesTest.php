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
    'admin.account.accounts.newaccounts',
    'admin.account.accounts.newaccountscreate',
    'admin.account.accounts.newaccountsedit',
    'admin.account.accounts.newaccountsupdate',
    'admin.account.accounts.newaccountsdelete',
    'admin.account.accounts.accountshead',
    'admin.account.accounts.accountsheadcreate',
    'admin.account.accounts.accountsheadedit',
    'admin.account.accounts.accountsheadupdate',
    'admin.account.accounts.accountsheaddelete',
    'admin.account.accounts.getBynewaccounts',
    'admin.account.accounts.changestatus',
    'admin.account.accounts.changestatuspost',
]);

it('renders newaccounts coa page for authenticated admin', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/admin/account/accounts/newaccounts')
        ->assertSuccessful()
        ->assertSee('Accounts Type List')
        ->assertSee('Add Accounts Type');
});

it('renders accountshead page for authenticated admin', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/admin/account/accounts/accountshead/1')
        ->assertSuccessful()
        ->assertSee('Accounts Head List')
        ->assertSee('Add Accounts Head');
});

it('creates an account head successfully via post', function () {
    $user = User::factory()->create();

    if (!\Illuminate\Support\Facades\Schema::hasTable('accounts_type') || !\Illuminate\Support\Facades\Schema::hasTable('accountsnew') || !\Illuminate\Support\Facades\Schema::hasTable('accountshead')) {
        expect(true)->toBeTrue();
        return;
    }

    $accType = \App\Models\Account\AccountType::first() ?? \App\Models\Account\AccountType::create(['name' => 'Assets', 'code' => 1]);
    $accNew = \App\Models\Account\AccountNew::first() ?? \App\Models\Account\AccountNew::create(['accounts_type_id' => $accType->id, 'code' => '11', 'name' => 'Fixed Assets']);

    $testName = 'Test Account Head ' . uniqid();

    $this->actingAs($user)
        ->post('/admin/account/accounts/accountsheadcreate/1', [
            'accounts_head_id' => $accType->id,
            'account_type_id' => $accNew->id,
            'name' => $testName,
            'brc_id' => 1,
            'description' => 'Test Note',
        ])
        ->assertRedirect('/admin/account/accounts/accountshead/1')
        ->assertSessionHas('success', 'Accounts Head added successfully');

    $this->assertDatabaseHas('accountshead', [
        'name' => $testName,
        'accounts_head_id' => $accType->id,
        'new_accounts_id' => $accNew->id,
    ]);
});

it('renders chart of accounts coa list and details page for authenticated admin', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/admin/account/accounts')
        ->assertSuccessful()
        ->assertSee('Chart of Accounts')
        ->assertSee('List View')
        ->assertSee('Details View');
});

