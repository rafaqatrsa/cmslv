@extends('user.layouts.app')

@section('title', 'Fees')

@section('content')
    @include('user.partials.nav', ['moduleKey' => 'fees'])

    <section class="mb-4 rounded border border-neutral-200 bg-white p-4">
        <h2 class="mb-3 text-lg font-semibold">Fee Totals</h2>
        <div class="grid gap-3 sm:grid-cols-3">
            <p>Assigned: {{ number_format($feeSummary['totals']['assigned_amount'], 2) }}</p>
            <p>Paid: {{ number_format($feeSummary['totals']['paid_amount'], 2) }}</p>
            <p>Balance: {{ number_format($feeSummary['totals']['balance'], 2) }}</p>
        </div>
    </section>

    <section class="overflow-x-auto rounded border border-neutral-200 bg-white">
        <div class="border-b border-neutral-200 px-4 py-3">
            <p class="text-sm text-neutral-500">Legacy tables: student_fees_assign, student_fees_deposite_details</p>
        </div>

        <table class="w-full text-left text-sm">
            <thead class="bg-neutral-50">
                <tr>
                    <th class="px-4 py-3">Bill No</th>
                    <th class="px-4 py-3">Fee Month</th>
                    <th class="px-4 py-3">Amount</th>
                    <th class="px-4 py-3">Paid</th>
                    <th class="px-4 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                @forelse ($feeSummary['deposits'] as $deposit)
                    <tr>
                        <td class="px-4 py-3">{{ $deposit->bill_no }}</td>
                        <td class="px-4 py-3">{{ $deposit->fee_month }}</td>
                        <td class="px-4 py-3">{{ number_format((float) $deposit->amount, 2) }}</td>
                        <td class="px-4 py-3">{{ number_format((float) $deposit->paid_amount, 2) }}</td>
                        <td class="px-4 py-3">{{ $deposit->status }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-neutral-600">No fee deposit records found for the selected student.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
@endsection
