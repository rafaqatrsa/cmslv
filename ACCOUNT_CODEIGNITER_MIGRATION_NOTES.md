# Accounts CodeIgniter Migration Notes

The Laravel workspace does not contain the original CodeIgniter Accounts controllers, models, views, helpers, libraries, JavaScript, DataTables endpoints, voucher-number generators, report SQL, or accounting formulas. A workspace search for legacy account controller names, `/admin/account`, voucher method names, and CodeIgniter branch helpers returned no source files.

This implementation therefore preserves the known legacy URLs and creates a Laravel 12, table-backed conversion foundation without inventing accounting rules. Transaction posting, AJAX response contracts, voucher numbering, report formulas, payroll formulas, student-fee calculations, royalty calculations, and stock valuation must be completed after the original CodeIgniter source is provided.

## Migration Map

| Legacy URL | CodeIgniter Controller | CodeIgniter Method | CodeIgniter Model Method | Database Tables | Accounting Effect | Stock Effect | Laravel Controller | Laravel Method | Laravel Route Name | Laravel Model or Service | Blade View | Permission Key |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| `/admin/account/accounts` | Missing source | `index` | Missing source | `accountshead`, `accountsnew`, `accounts_type` | Unknown | None found | `AccountController` | `index` | `admin.account.accounts.index` | `AccountHead`, `AccountIndexService` | `admin.account.modules.index` | `accounts` |
| `/admin/account/brands` | Missing source | `index` | Missing source | `brands` | None found | Product master data | `BrandController` | `index` | `admin.account.brands.index` | `Brand`, `AccountIndexService` | `admin.account.modules.index` | `brands` |
| `/admin/account/classbooksets` | Missing source | `index` | Missing source | `sale_bookset_bill`, `sale_bookset_bill_detail` | Unknown student receivable/payment rules | Unknown book-set stock issue rules | `ClassBookSetController` | `index` | `admin.account.class-book-sets.index` | `ClassBookSet`, `InvoiceBookSetItem` | `admin.account.modules.index` | `classbooksets` |
| `/admin/account/contra` | Missing source | `index` | Missing source | `contra_voucher`, `contra_voucher_details` | Debit and credit must balance | None found | `ContraVoucherController` | `index` | `admin.account.contra.index` | `ContraVoucher`, `VoucherPostingService` | `admin.account.modules.index` | `contra` |
| `/admin/account/documentsacc` | Missing source | `index` | Missing source | `upload_contents` | None found | None found | `AccountDocumentController` | `index` | `admin.account.documents.index` | `AccountDocument` | `admin.account.modules.index` | `documentsacc` |
| `/admin/account/expenses` | Missing source | `index` | Missing source | `expenses_bill`, `expenses_bill_items` | Expense voucher rules unknown | None found | `ExpenseController` | `index` | `admin.account.expenses.index` | `Expense`, `ExpenseItem`, `VoucherPostingService` | `admin.account.modules.index` | `expenses` |
| `/admin/account/feemaster` | Missing source | `index` | Missing source | `fee_class_groups`, `fee_groups_feetype` | Fee mapping rules unknown | None found | `FeeMasterController` | `index` | `admin.account.fee-master.index` | `FeeMaster` | `admin.account.modules.index` | `feemaster` |
| `/admin/account/invoicebooksets` | Missing source | `index` | Missing source | `sale_bookset_bill`, `sale_bookset_bill_detail` | Unknown receivable/payment rules | Unknown stock issue rules | `InvoiceBookSetController` | `index` | `admin.account.invoice-book-sets.index` | `InvoiceBookSet`, `InvoiceBookSetItem` | `admin.account.modules.index` | `invoicebooksets` |
| `/admin/account/invoicebooksetreturns` | Missing source | `index` | Missing source | `sale_bookset_return`, `sale_bookset_return_detail` | Unknown refund/reversal rules | Unknown stock return rules | `InvoiceBookSetReturnController` | `index` | `admin.account.invoice-book-set-returns.index` | `InvoiceBookSetReturn`, `InvoiceBookSetReturnItem` | `admin.account.modules.index` | `invoicebooksetreturns` |
| `/admin/account/itemcategory` | Missing source | `index` | Missing source | `item_category` | None found | Product master data | `ItemCategoryController` | `index` | `admin.account.item-categories.index` | `ItemCategory` | `admin.account.modules.index` | `itemcategory` |
| `/admin/account/journalvoucher` | Missing source | `index` | Missing source | `journal_voucher`, `journal_voucher_detail` | Debit and credit must balance | None found | `JournalVoucherController` | `index` | `admin.account.journal-vouchers.index` | `JournalVoucher`, `JournalVoucherDetail`, `VoucherPostingService` | `admin.account.modules.index` | `journalvoucher` |
| `/admin/account/payments` | Missing source | `index` | Missing source | `payments_voucher` | Payment voucher rules unknown | None found | `PaymentVoucherController` | `index` | `admin.account.payments.index` | `PaymentVoucher`, `VoucherPostingService` | `admin.account.modules.index` | `payments` |
| `/admin/account/payroll` | Missing source | `index` | Missing source | `staff`; no payroll table found | Payroll formula unknown | None found | `PayrollController` | `index` | `admin.account.payroll.index` | `Staff` | `admin.account.modules.index` | `payroll` |
| `/admin/account/products` | Missing source | `index` | Missing source | `products`, `productsaccountshead` | Product account mappings exist | Product quantity exists | `ProductController` | `index` | `admin.account.products.index` | `Product`, `InventoryService` | `admin.account.modules.index` | `products` |
| `/admin/account/producttype` | Missing source | `index` | Missing source | `product_type` | None found | Product master data | `ProductTypeController` | `index` | `admin.account.product-types.index` | `ProductType` | `admin.account.modules.index` | `producttype` |
| `/admin/account/purchases` | Missing source | `index` | Missing source | `purchases`, `purchase_items`, `payments_voucher` | Payable/voucher rules unknown | Purchase stock rules unknown | `PurchaseController` | `index` | `admin.account.purchases.index` | `Purchase`, `PurchaseItem` | `admin.account.modules.index` | `purchases` |
| `/admin/account/purchasereturns` | Missing source | `index` | Missing source | `purchases`, `purchase_items` | Purchase return rules unknown | Stock reversal rules unknown | `PurchaseReturnController` | `index` | `admin.account.purchase-returns.index` | `PurchaseReturn` | `admin.account.modules.index` | `purchasereturns` |
| `/admin/account/receipts` | Missing source | `index` | Missing source | `receipts_voucher` | Receipt voucher rules unknown | None found | `ReceiptVoucherController` | `index` | `admin.account.receipts.index` | `ReceiptVoucher`, `VoucherPostingService` | `admin.account.modules.index` | `receipts` |
| `/admin/account/royalty` | Missing source | `index` | Missing source | `branch_royalty_scenarios`, `royalty_deposite_details` | Royalty posting rules unknown | None found | `RoyaltyController` | `index` | `admin.account.royalty.index` | `RoyaltyDeposit` | `admin.account.modules.index` | `royalty` |
| `/admin/account/sales` | Missing source | `index` | Missing source | `sales`, `sale_items`, `receipts_voucher` | Receivable/voucher rules unknown | Sale stock rules unknown | `SaleController` | `index` | `admin.account.sales.index` | `Sale`, `SaleItem` | `admin.account.modules.index` | `sales` |
| `/admin/account/salesreturns` | Missing source | `index` | Missing source | `sales`, `sale_items` | Sale return rules unknown | Stock return rules unknown | `SaleReturnController` | `index` | `admin.account.sales-returns.index` | `SaleReturn` | `admin.account.modules.index` | `salesreturns` |
| `/admin/account/stock` | Missing source | `index` | Missing source | `product_stock_history`, `products` | None found | Stock history exists; valuation unknown | `StockController` | `index` | `admin.account.stock.index` | `StockMovement`, `InventoryService` | `admin.account.modules.index` | `stock` |
| `/admin/account/studentfee` | Missing source | `index` | Missing source | `student_fees_assign`, `student_fees_deposite`, `student_fees_deposite_details` | Fee receipt/waiver rules unknown | None found | `StudentFeeController` | `index` | `admin.account.student-fees.index` | `StudentFeeDeposit` | `admin.account.modules.index` | `studentfee` |
| `/admin/account/supplier` | Missing source | `index` | Missing source | `supplier` | Supplier account mapping exists | Supplier purchase relationship unknown | `SupplierController` | `index` | `admin.account.suppliers.index` | `Supplier` | `admin.account.modules.index` | `supplier` |
| `/admin/account/units` | Missing source | `index` | Missing source | `units` | None found | Product unit master data | `UnitController` | `index` | `admin.account.units.index` | `Unit` | `admin.account.modules.index` | `units` |

## Implemented Laravel Files

- Routes: `routes/web.php`
- Controllers: `app/Http/Controllers/Admin/Account/*Controller.php`
- Models: `app/Models/Account/*.php`
- Services: `app/Services/Account/AccountIndexService.php`, `AccountModuleRegistry.php`, `VoucherPostingService.php`, `LedgerService.php`, `InventoryService.php`
- Context: `app/Services/FinancialYearContext.php`
- Middleware: `app/Http/Middleware/FinancialYearMiddleware.php`
- Views: `resources/views/admin/account/partials/nav.blade.php`, `resources/views/admin/account/modules/index.blade.php`
- Tests: `tests/Feature/AccountLegacyRoutesTest.php`, `tests/Unit/VoucherPostingServiceTest.php`

## Unresolved Dependencies

- Original CodeIgniter Accounts controllers and method list
- Original CodeIgniter models and query methods
- Original views, modal partials, print layouts, DataTables JavaScript, and AJAX response shapes
- Voucher numbering and posting status workflow
- General-ledger destination table; no `general_ledger` or `ledger` table was found
- Financial-year table semantics beyond `adcademicyear`
- Payroll tables/formulas; no payroll/salary table was found by schema filter
- Stock valuation method and negative-stock configuration
- Purchase, sale, return, book-set, royalty, and student-fee formulas
- Permission table rows for exact add/edit/delete action keys

## Artisan Commands Used Or Relevant

```bash
php artisan route:list --path=admin/account
vendor/bin/pint --dirty --format agent
php artisan test --compact tests/Feature/AccountLegacyRoutesTest.php tests/Unit/VoucherPostingServiceTest.php
```
