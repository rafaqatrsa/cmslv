@foreach (['success', 'status', 'error'] as $flashType)
    @if (session($flashType))
        <div class="mb-4 rounded border px-4 py-3 text-sm {{ $flashType === 'error' ? 'border-red-200 bg-red-50 text-red-800' : 'border-green-200 bg-green-50 text-green-800' }}">
            {{ session($flashType) }}
        </div>
    @endif
@endforeach

@if ($errors->any())
    <div class="mb-4 rounded border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        <p class="font-medium">Please correct the highlighted fields.</p>
        <ul class="mt-2 list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
