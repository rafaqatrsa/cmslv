@inject('userRegistry', 'App\Services\User\UserModuleRegistry')

<nav class="mb-4 flex flex-wrap gap-2">
    <a href="{{ route('user.dashboard') }}" class="rounded border px-3 py-2 text-sm {{ ($moduleKey ?? null) === 'dashboard' ? 'border-blue-600 bg-blue-50 text-blue-700' : 'border-neutral-200 bg-white text-neutral-700' }}">Dashboard</a>
    <a href="{{ route('user.profile.show') }}" class="rounded border px-3 py-2 text-sm {{ ($moduleKey ?? null) === 'profile' ? 'border-blue-600 bg-blue-50 text-blue-700' : 'border-neutral-200 bg-white text-neutral-700' }}">Profile</a>
    <a href="{{ route('user.fees.index') }}" class="rounded border px-3 py-2 text-sm {{ ($moduleKey ?? null) === 'fees' ? 'border-blue-600 bg-blue-50 text-blue-700' : 'border-neutral-200 bg-white text-neutral-700' }}">Fees</a>

    @foreach ($userRegistry->all() as $key => $registeredModule)
        @continue($key === 'dashboard')

        <a
            href="{{ route($registeredModule['route']) }}"
            class="rounded border px-3 py-2 text-sm {{ ($moduleKey ?? null) === $key ? 'border-blue-600 bg-blue-50 text-blue-700' : 'border-neutral-200 bg-white text-neutral-700' }}"
        >
            {{ $registeredModule['label'] }}
        </a>
    @endforeach

    <a href="{{ route('user.password.edit') }}" class="rounded border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-700">Password</a>
    <a href="{{ route('user.username.edit') }}" class="rounded border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-700">Username</a>
</nav>
