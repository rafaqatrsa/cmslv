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
]);
