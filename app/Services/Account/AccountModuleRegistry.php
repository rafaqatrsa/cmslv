<?php

namespace App\Services\Account;

use App\Models\Account\AccountHead;
use App\Models\Account\AccountDocument;
use App\Models\Account\Brand;
use App\Models\Account\ClassBookSet;
use App\Models\Account\ContraVoucher;
use App\Models\Account\Expense;
use App\Models\Account\FeeMaster;
use App\Models\Account\InvoiceBookSet;
use App\Models\Account\InvoiceBookSetReturn;
use App\Models\Account\ItemCategory;
use App\Models\Account\JournalVoucher;
use App\Models\Account\PaymentVoucher;
use App\Models\Account\Product;
use App\Models\Account\ProductType;
use App\Models\Account\Purchase;
use App\Models\Account\PurchaseReturn;
use App\Models\Account\ReceiptVoucher;
use App\Models\Account\RoyaltyDeposit;
use App\Models\Account\Sale;
use App\Models\Account\SaleReturn;
use App\Models\Account\StockMovement;
use App\Models\Account\StudentFeeDeposit;
use App\Models\Account\Supplier;
use App\Models\Account\Unit;
use App\Models\Staff;

class AccountModuleRegistry
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public function all(): array
    {
        return [
            'accounts' => $this->module('Chart of Accounts', AccountHead::class, 'accountshead', 'admin.account.accounts.index', 'accounts', ['code', 'name', 'note'], ['code', 'name', 'accounts_head_id', 'new_accounts_id', 'is_active']),
            'accounts-type' => $this->module('Accounts Type', \App\Models\Account\AccountNew::class, 'accountsnew', 'admin.account.accounts.newaccounts', 'accounts', ['code', 'name', 'note'], ['code', 'name', 'accounts_type_id', 'is_active']),
            'brands' => $this->module('Brands', Brand::class, 'brands', 'admin.account.brands.index', 'brands', ['code', 'name', 'description'], ['code', 'name', 'slug', 'is_active']),
            'class-book-sets' => $this->module('Class Book Sets', ClassBookSet::class, 'sale_bookset_bill', 'admin.account.class-book-sets.index', 'classbooksets', ['invoice_no', 'student_name', 'father_name'], ['invoice_no', 'date', 'student_name', 'class_id', 'grand_total']),
            'contra' => $this->module('Contra Vouchers', ContraVoucher::class, 'contra_voucher', 'admin.account.contra.index', 'contra', ['voucher_no', 'note'], ['voucher_no', 'date', 'acc_head_id', 'amount', 'created_by']),
            'documents' => $this->module('Account Documents', AccountDocument::class, 'upload_contents', 'admin.account.documents.index', 'documentsacc', ['real_name', 'img_name', 'vid_title'], ['real_name', 'file_type', 'file_size', 'created_at']),
            'expenses' => $this->module('Expenses', Expense::class, 'expenses_bill', 'admin.account.expenses.index', 'expenses', ['invoice_no', 'paid_to', 'note'], ['invoice_no', 'date', 'paid_to', 'grand_total', 'is_active']),
            'fee-master' => $this->module('Fee Master', FeeMaster::class, 'fee_groups_feetype', 'admin.account.fee-master.index', 'feemaster', ['frequency', 'note'], ['fee_class_id', 'feetype_id', 'amount', 'frequency', 'session_id']),
            'fee-revise' => $this->module('Fee Revise', StudentFeeDeposit::class, 'student_fees_assign', 'admin.account.studentfee.feerevise', 'studentfee', ['admission_no', 'firstname', 'lastname'], ['admission_no', 'firstname', 'lastname', 'current_amount']),
            'assign-dues' => $this->module('Assign Dues', StudentFeeDeposit::class, 'student_fees_deposite_details', 'admin.account.studentfee.assigndues', 'studentfee', ['bill_no', 'note'], ['bill_no', 'date', 'student_id', 'amount', 'paid_amount']),
            'assign-fee-voucher' => $this->module('Assign Fee Voucher', StudentFeeDeposit::class, 'student_fees_deposite', 'admin.account.studentfee.assignfeevoucher', 'studentfee', ['bill_no', 'note'], ['bill_no', 'date', 'student_id', 'amount', 'paid_amount']),
            'assign-fee-voucher-date-wise' => $this->module('Assign Fee Voucher Date Wise', StudentFeeDeposit::class, 'student_fees_deposite', 'admin.account.studentfee.assignfeevoucherdatewise', 'studentfee', ['bill_no', 'note'], ['bill_no', 'date', 'student_id', 'amount', 'paid_amount']),
            'fee-voucher-student-sibling' => $this->module('Fee Voucher Student Sibling', StudentFeeDeposit::class, 'student_fees_deposite', 'admin.account.studentfee.feevoucherstudentsibling', 'studentfee', ['bill_no', 'note'], ['bill_no', 'date', 'student_id', 'amount', 'paid_amount']),
            'fee-voucher' => $this->module('Fee Voucher', StudentFeeDeposit::class, 'student_fees_deposite', 'admin.account.studentfee.feevoucher', 'studentfee', ['bill_no', 'note'], ['bill_no', 'date', 'student_id', 'amount', 'paid_amount']),
            'custom-fee-voucher' => $this->module('Custom Fee Voucher', StudentFeeDeposit::class, 'student_fees_deposite', 'admin.account.studentfee.customfeevoucher', 'studentfee', ['bill_no', 'note'], ['bill_no', 'date', 'student_id', 'amount', 'paid_amount']),
            'invoice-book-sets' => $this->module('Invoice Book Sets', InvoiceBookSet::class, 'sale_bookset_bill', 'admin.account.invoice-book-sets.index', 'invoicebooksets', ['invoice_no', 'student_name'], ['invoice_no', 'date', 'student_name', 'grand_total', 'paid_amount']),
            'invoice-book-set-returns' => $this->module('Invoice Book Set Returns', InvoiceBookSetReturn::class, 'sale_bookset_return', 'admin.account.invoice-book-set-returns.index', 'invoicebooksetreturns', ['return_no', 'invoice_no_ref', 'student_name'], ['return_no', 'date', 'invoice_no_ref', 'student_name', 'total_amount']),
            'item-categories' => $this->module('Item Categories', ItemCategory::class, 'item_category', 'admin.account.item-categories.index', 'itemcategory', ['code', 'name', 'description'], ['code', 'name', 'parent_id', 'slug', 'is_active']),
            'journal-vouchers' => $this->module('Journal Vouchers', JournalVoucher::class, 'journal_voucher', 'admin.account.journal-vouchers.index', 'journalvoucher', ['voucher_no', 'note'], ['voucher_no', 'date', 'note', 'created_by']),
            'payments' => $this->module('Payment Vouchers', PaymentVoucher::class, 'payments_voucher', 'admin.account.payments.index', 'payments', ['name', 'invoice_no', 'note'], ['invoice_no', 'date', 'supplier_id', 'debit_amount', 'credit_amount']),
            'payroll' => $this->module('Payroll', Staff::class, 'staff', 'admin.account.payroll.index', 'payroll', ['employee_id', 'name', 'surname'], ['employee_id', 'name', 'surname', 'role_id', 'is_active']),
            'products' => $this->module('Products', Product::class, 'products', 'admin.account.products.index', 'products', ['code', 'name', 'slug', 'details'], ['code', 'name', 'quantity', 'cost', 'price', 'is_active']),
            'product-types' => $this->module('Product Types', ProductType::class, 'product_type', 'admin.account.product-types.index', 'producttype', ['code', 'name', 'description'], ['code', 'name', 'parent_id', 'slug', 'is_active']),
            'purchases' => $this->module('Purchases', Purchase::class, 'purchases', 'admin.account.purchases.index', 'purchases', ['purchase_no', 'supplier', 'note'], ['purchase_no', 'date', 'supplier', 'grand_total', 'payment_status']),
            'purchase-returns' => $this->module('Purchase Returns', PurchaseReturn::class, 'purchases', 'admin.account.purchase-returns.index', 'purchasereturns', ['return_purchase_ref', 'supplier', 'note'], ['purchase_no', 'date', 'return_purchase_ref', 'return_purchase_total', 'status']),
            'receipts' => $this->module('Receipt Vouchers', ReceiptVoucher::class, 'receipts_voucher', 'admin.account.receipts.index', 'receipts', ['name', 'invoice_no', 'note'], ['invoice_no', 'date', 'customer_id', 'debit_amount', 'credit_amount']),
            'royalty' => $this->module('Royalty', RoyaltyDeposit::class, 'royalty_deposite_details', 'admin.account.royalty.index', 'royalty', ['bill_no', 'note'], ['bill_no', 'date', 'royalty_month', 'amount', 'paid_amount']),
            'sales' => $this->module('Sales', Sale::class, 'sales', 'admin.account.sales.index', 'sales', ['sale_no', 'customer', 'note'], ['sale_no', 'date', 'customer', 'grand_total', 'payment_status']),
            'sales-returns' => $this->module('Sale Returns', SaleReturn::class, 'sales', 'admin.account.sales-returns.index', 'salesreturns', ['return_sale_ref', 'customer', 'note'], ['sale_no', 'date', 'return_sale_ref', 'return_sale_total', 'status']),
            'stock' => $this->module('Stock', StockMovement::class, 'product_stock_history', 'admin.account.stock.index', 'stock', ['note'], ['product_id', 'stock_date', 'quantity_added', 'brc_id', 'created_by']),
            'student-fees' => $this->module('Student Fees', StudentFeeDeposit::class, 'student_fees_deposite_details', 'admin.account.student-fees.index', 'studentfee', ['bill_no', 'note'], ['bill_no', 'date', 'student_id', 'amount', 'paid_amount']),
            'suppliers' => $this->module('Suppliers', Supplier::class, 'supplier', 'admin.account.suppliers.index', 'supplier', ['supplier_id', 'name', 'phone', 'email', 'company'], ['supplier_id', 'name', 'phone', 'email', 'company']),
            'units' => $this->module('Units', Unit::class, 'units', 'admin.account.units.index', 'units', ['code', 'name', 'description'], ['code', 'name', 'base_unit', 'operator', 'is_active']),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function get(string $key): array
    {
        return $this->all()[$key];
    }

    /**
     * @param  class-string  $model
     * @param  array<int, string>  $search
     * @param  array<int, string>  $columns
     * @return array<string, mixed>
     */
    private function module(string $label, string $model, string $table, string $route, string $permission, array $search, array $columns): array
    {
        return compact('label', 'model', 'table', 'route', 'permission', 'search', 'columns');
    }
}
