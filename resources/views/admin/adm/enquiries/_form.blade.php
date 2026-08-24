            <form method="POST" action="{{ route('admin.adm.enquiries.store', absolute: false) }}" class="space-y-4 p-4" id="{{ $formId ?? 'admission-enquiry-form' }}">
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
