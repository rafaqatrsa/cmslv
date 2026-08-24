@extends('admin.layouts.app')

@section('title', 'Add Admission Enquiry')

@section('content')
    <div class="space-y-4">
        <section class="rounded border border-neutral-300 bg-white shadow-sm">
            <div class="flex items-center justify-between gap-3 border-b border-neutral-300 px-4 py-3">
                <h1 class="text-xl font-semibold text-neutral-800"><i class="fa fa-ioxhost mr-2 text-[#26408d]"></i>Admission Enquiry</h1>
                <a href="{{ route('admin.adm.enquiries.index', absolute: false) }}" class="rounded bg-[#26408d] px-4 py-2 text-sm font-semibold text-white">Back</a>
            </div>

            <form method="POST" action="{{ route('admin.adm.enquiries.store', absolute: false) }}" class="space-y-4 p-4" id="admission-enquiry-form">
                @csrf
                <input type="hidden" name="enquiry_no" value="{{ old('enquiry_no', $enquiryNo) }}">

                @if ($errors->any())
                    <div class="rounded border border-red-300 bg-red-50 p-3 text-sm text-red-700">
                        <ul class="list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                <section class="rounded border border-neutral-300">
                    <h2 class="bg-[#26408d] px-3 py-2 text-sm font-semibold text-white">Visitor Information</h2>
                    <div class="grid gap-3 p-4 md:grid-cols-2 xl:grid-cols-3">
                        <label class="field"><span>Branch <b>*</b></span><select name="brc_id" class="control"><option value="">Select</option>@foreach ($branches as $branch)<option value="{{ $branch->id }}" @selected((string) old('brc_id', $branchId) === (string) $branch->id)>{{ $branch->name }}</option>@endforeach</select></label>
                        <label class="field"><span>Name of Visitor <b>*</b></span><input name="name" value="{{ old('name') }}" class="control" required></label>
                        <label class="field"><span>Phone <b>*</b></span><div class="flex"><select name="country_code" class="control w-24"><option value="+92">+92</option><option value="+1">+1</option><option value="+44">+44</option></select><input name="contact" value="{{ old('contact') }}" class="control rounded-l-none" required></div><small id="phone-error-message" class="text-red-600"></small></label>
                        <label class="field"><span>ID Card</span><input name="idcard" value="{{ old('idcard') }}" placeholder="34101xxxxxxxx" maxlength="13" class="control"></label>
                        <label class="field"><span>Visitor Relation</span><select name="visitor_relation" class="control"><option value="">Select</option><option value="1">Father</option><option value="2">Mother</option><option value="3">Other</option></select></label>
                        <label class="field"><span>Email</span><input type="email" name="email" value="{{ old('email') }}" class="control"></label>
                    </div>
                </section>

                <section class="rounded border border-neutral-300">
                    <div class="flex items-center justify-between bg-[#26408d] px-3 py-2 text-sm font-semibold text-white"><span>Proposed Kids for Admission</span><button type="button" id="add-kid" class="rounded bg-white px-2 py-1 text-xs font-bold text-[#26408d]"><i class="fa fa-plus"></i> Add</button></div>
                    <div id="kids" class="space-y-3 p-4">
                        <div class="kid-row grid gap-3 rounded border border-neutral-200 p-3 md:grid-cols-[1fr_1fr_160px_auto]">
                            <label class="field"><span>Class</span><select name="class_id[]" class="control kid-class"><option value="">Select</option>@foreach ($classes as $class)<option value="{{ $class->id }}">{{ $class->class }}</option>@endforeach</select></label>
                            <label class="field"><span>Kid Name</span><input name="kid_name[]" class="control"></label>
                            <label class="field"><span>Number of Kids</span><input type="number" name="number_of_kids[]" value="1" min="1" class="control"></label>
                            <button type="button" class="show-fees self-end rounded bg-[#11b256] px-3 py-2 text-xs font-bold text-white">Show Fees</button>
                        </div>
                    </div>
                    <div id="fee-preview" class="hidden border-t border-neutral-200 p-4"></div>
                </section>

                <section class="rounded border border-neutral-300">
                    <h2 class="bg-[#26408d] px-3 py-2 text-sm font-semibold text-white">Father / Guardian Guidance</h2>
                    <div class="grid gap-3 p-4 md:grid-cols-2 xl:grid-cols-3">
                        <label class="field"><span>Father Name</span><input name="father_name" value="{{ old('father_name') }}" class="control"></label>
                        <label class="field"><span>Occupation</span><div class="flex"><select name="occupation_id" class="control inline-create-select"><option value="">Select</option>@foreach ($occupations as $occupation)<option value="{{ $occupation->id }}">{{ $occupation->name }}</option>@endforeach</select><button type="button" class="inline-create-button" data-inline-create-url="{{ route('admin.adm.enquiries.occupation.store', absolute: false) }}" data-inline-create-label="Occupation">+</button></div></label>
                        <label class="field"><span>Address</span><input name="address" value="{{ old('address') }}" class="control"></label>
                        <label class="field"><span>Landline Number</span><input name="landline_no" value="{{ old('landline_no') }}" class="control"></label>
                        <label class="field"><span>Phone</span><input name="phone" value="{{ old('phone') }}" class="control"></label>
                        <label class="field"><span>WhatsApp</span><div class="flex"><select name="whatsapp_country_code" class="control w-24"><option value="+92">+92</option><option value="+1">+1</option></select><input name="whatsapp" value="{{ old('whatsapp') }}" class="control rounded-l-none"></div></label>
                    </div>
                </section>

                <section class="rounded border border-neutral-300">
                    <h2 class="bg-[#26408d] px-3 py-2 text-sm font-semibold text-white">How did you hear about us?</h2>
                    <div class="grid gap-3 p-4 md:grid-cols-2">
                        <label class="field"><span>Reference</span><div class="flex"><select name="reference" class="control inline-create-select"><option value="">Select</option>@foreach ($references as $reference)<option value="{{ $reference->id }}">{{ $reference->reference }}</option>@endforeach</select><button type="button" class="inline-create-button" data-inline-create-url="{{ route('admin.adm.enquiries.reference.store', absolute: false) }}" data-inline-create-label="Reference">+</button></div></label>
                        <label class="field"><span>Source <b>*</b></span><div class="flex"><select name="source" class="control inline-create-select" required><option value="">Select</option>@foreach ($sources as $source)<option value="{{ $source->id ?? $source->source }}">{{ $source->source }}</option>@endforeach</select><button type="button" class="inline-create-button" data-inline-create-url="{{ route('admin.adm.enquiries.source.store', absolute: false) }}" data-inline-create-label="Source">+</button></div></label>
                    </div>
                </section>

                <section class="rounded border border-neutral-300">
                    <h2 class="bg-[#26408d] px-3 py-2 text-sm font-semibold text-white">Follow Up</h2>
                    <div class="grid gap-3 p-4 md:grid-cols-3">
                        <label class="field"><span>Date <b>*</b></span><input type="date" name="date" value="{{ old('date', now()->toDateString()) }}" class="control" required></label>
                        <label class="field"><span>Next Follow Up Date <b>*</b></span><input type="date" name="follow_up_date" value="{{ old('follow_up_date', now()->toDateString()) }}" class="control" required></label>
                        <label class="field"><span>Assigned To</span><select name="assigned" class="control"><option value="">Select</option>@foreach ($staff as $member)<option value="{{ $member->id }}">{{ $member->name }}</option>@endforeach</select></label>
                        <label class="field md:col-span-1"><span>Description</span><textarea name="description" class="control min-h-24">{{ old('description') }}</textarea></label>
                        <label class="field md:col-span-2"><span>Note</span><textarea name="note" class="control min-h-24">{{ old('note') }}</textarea></label>
                    </div>
                </section>

                <section class="rounded border border-neutral-300">
                    <h2 class="bg-[#26408d] px-3 py-2 text-sm font-semibold text-white">Fee Packages Policy</h2>
                    <div class="grid gap-3 p-4 md:grid-cols-2">
                        <label class="field"><span>Sibling Discount</span><div class="flex gap-4 pt-2"><label><input type="radio" name="fee_policy_sibling_discount_type" value="amount" checked> Fixed Amount</label><label><input type="radio" name="fee_policy_sibling_discount_type" value="percentage"> Percentage</label></div></label>
                        <label class="field"><span>Waived Off</span><div class="flex gap-4 pt-2"><label><input type="radio" name="fee_policy_waived_off_type" value="amount" checked> Fixed Amount</label><label><input type="radio" name="fee_policy_waived_off_type" value="percentage"> Percentage</label></div></label>
                    </div>
                </section>

                <div class="flex items-center justify-end gap-4 border-t border-neutral-200 pt-4"><label class="text-sm"><input type="checkbox" name="notification" value="notification" checked> Admission enquiry notification</label><button type="submit" class="rounded bg-[#11b256] px-6 py-2.5 font-bold text-white hover:bg-[#0e9448]" data-submit-button><i class="fa fa-save mr-1"></i> Save</button></div>
            </form>
        </section>
    </div>

    <div id="inline-create-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
        <div class="w-full max-w-md rounded bg-white p-5 shadow-xl">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold">Add <span data-inline-create-title>Option</span></h2>
                <button type="button" class="text-2xl" data-inline-create-close aria-label="Close">×</button>
            </div>
            <form data-inline-create-form>
                <label class="field"><span data-inline-create-label>Name</span><input name="name" class="control" required maxlength="150"></label>
                <p class="mt-2 text-sm text-red-600" data-inline-create-error></p>
                <button type="submit" class="mt-4 rounded bg-[#26408d] px-4 py-2 font-semibold text-white">Save</button>
            </form>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .field { display: block; }
        .field > span { display: block; margin-bottom: 6px; font-size: 14px; font-weight: 700; color: #1f2937; }
        .field b { color: #dc2626; }
        .control { width: 100%; min-height: 42px; border: 1px solid #cfcfcf; border-radius: 3px; background: #fff; padding: 8px 12px; color: #374151; outline: none; }
        .control:focus { border-color: #26408d; box-shadow: 0 0 0 3px rgba(38, 64, 141, .1); }
        .inline-create-button { min-width: 42px; border: 0; background: #26408d; color: #fff; font-weight: 700; }
    </style>
@endpush

@push('scripts')
    <script>
        (() => {
            const form = document.querySelector('#admission-enquiry-form');
            const phone = form?.querySelector('[name="contact"]');
            const countryCode = form?.querySelector('[name="country_code"]');
            const phoneError = document.querySelector('#phone-error-message');
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            const inlineModal = document.querySelector('#inline-create-modal');
            let inlineTrigger;

            const notify = (message, type = 'success') => {
                if (window.Swal) {
                    window.Swal.fire({ icon: type, title: type === 'success' ? 'Success' : 'Error', text: message, timer: 1800, showConfirmButton: false });
                    return;
                }
                window.alert(message);
            };

            phone?.addEventListener('blur', async () => {
                phoneError.textContent = '';
                const number = (countryCode?.value || '') + (phone.value || '');
                if (!phone.value) return;
                const response = await fetch(@json(route('admin.adm.enquiries.check-number', absolute: false)), {
                    method: 'POST',
                    headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'X-Requested-With': 'XMLHttpRequest' },
                    body: JSON.stringify({ phone_number: number }),
                });
                const payload = await response.json();
                if (payload.status === 'success') phoneError.textContent = '(' + payload.message + ')';
            });

            form?.addEventListener('submit', async (event) => {
                event.preventDefault();
                const button = form.querySelector('[data-submit-button]');
                button.disabled = true;
                button.classList.add('opacity-60');
                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': token },
                    });
                    const payload = await response.json();
                    if (!response.ok) throw new Error(Object.values(payload.errors || {}).flat().join(' ') || payload.message || 'Unable to save enquiry.');
                    notify(payload.message);
                    window.location.href = payload.redirect_url;
                } catch (error) {
                    notify(error.message, 'error');
                } finally {
                    button.disabled = false;
                    button.classList.remove('opacity-60');
                }
            });

            document.addEventListener('click', (event) => {
                const trigger = event.target.closest('[data-inline-create-url]');
                if (trigger) {
                    inlineTrigger = trigger;
                    inlineModal.querySelector('[data-inline-create-title]').textContent = trigger.dataset.inlineCreateLabel;
                    inlineModal.querySelector('[data-inline-create-label]').textContent = trigger.dataset.inlineCreateLabel + ' Name';
                    inlineModal.querySelector('[name="name"]').value = '';
                    inlineModal.querySelector('[data-inline-create-error]').textContent = '';
                    inlineModal.classList.remove('hidden');
                    inlineModal.classList.add('flex');
                }
                if (event.target.closest('[data-inline-create-close]') || event.target === inlineModal) {
                    inlineModal.classList.add('hidden');
                    inlineModal.classList.remove('flex');
                }
            });

            inlineModal?.querySelector('[data-inline-create-form]')?.addEventListener('submit', async (event) => {
                event.preventDefault();
                const errorElement = inlineModal.querySelector('[data-inline-create-error]');
                try {
                    const response = await fetch(inlineTrigger.dataset.inlineCreateUrl, {
                        method: 'POST',
                        body: new FormData(event.currentTarget),
                        headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': token },
                    });
                    const payload = await response.json();
                    if (!response.ok || payload.status === 'fail') throw new Error(payload.error?.name || payload.message || 'Unable to add option.');
                    const select = inlineTrigger.closest('.field').querySelector('select');
                    select.append(new Option(payload.data.name, payload.data.id, true, true));
                    inlineModal.classList.add('hidden');
                    inlineModal.classList.remove('flex');
                    notify(payload.message);
                } catch (error) {
                    errorElement.textContent = error.message;
                }
            });

            const kids = document.querySelector('#kids');
            const addButton = document.querySelector('#add-kid');
            const feePreview = document.querySelector('#fee-preview');
            const classOptions = @json($classes->map(fn ($class) => ['id' => $class->id, 'name' => $class->class])->values());

            const options = classOptions.map((item) => `<option value="${item.id}">${item.name}</option>`).join('');

            addButton?.addEventListener('click', () => {
                const row = document.createElement('div');
                row.className = 'kid-row grid gap-3 rounded border border-neutral-200 p-3 md:grid-cols-[1fr_1fr_160px_auto]';
                row.innerHTML = `<label class="field"><span>Class</span><select name="class_id[]" class="control kid-class"><option value="">Select</option>${options}</select></label><label class="field"><span>Kid Name</span><input name="kid_name[]" class="control"></label><label class="field"><span>Number of Kids</span><input type="number" name="number_of_kids[]" value="1" min="1" class="control"></label><button type="button" class="remove-kid self-end rounded bg-red-500 px-3 py-2 text-xs font-bold text-white">Remove</button>`;
                kids.appendChild(row);
            });

            kids?.addEventListener('click', (event) => {
                if (event.target.closest('.remove-kid')) event.target.closest('.kid-row').remove();
                if (event.target.closest('.show-fees')) {
                    const row = event.target.closest('.kid-row');
                    const classId = row.querySelector('.kid-class')?.value;
                    if (!classId) { feePreview.classList.remove('hidden'); feePreview.textContent = 'Please select a class first.'; return; }
                    feePreview.classList.remove('hidden'); feePreview.textContent = 'Loading fee package...';
                    fetch(`{{ route('admin.adm.enquiries.fee-structure', absolute: false) }}?class_id=${classId}&brc_id={{ $branchId }}`)
                        .then((response) => response.json())
                        .then((rows) => { feePreview.innerHTML = rows.length ? `<div class="font-semibold">Fee Package</div><pre class="mt-2 overflow-auto text-xs">${JSON.stringify(rows, null, 2)}</pre>` : 'No fee package found for this class.'; })
                        .catch(() => { feePreview.textContent = 'Unable to load fee package right now.'; });
                }
            });
        })();
    </script>
@endpush
