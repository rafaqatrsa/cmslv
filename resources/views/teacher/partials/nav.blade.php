@inject('teacherRegistry', 'App\Services\Teacher\TeacherModuleRegistry')
@inject('teacherContext', 'App\Services\Teacher\TeacherContext')

<nav class="mb-4 flex flex-wrap gap-2">
    @foreach ($teacherRegistry->all() as $key => $registeredModule)
        @continue($key === 'profile')

        <a
            href="{{ route($registeredModule['route']) }}"
            class="rounded border px-3 py-2 text-sm {{ ($moduleKey ?? null) === $key ? 'border-blue-600 bg-blue-50 text-blue-700' : 'border-neutral-200 bg-white text-neutral-700' }}"
        >
            {{ $registeredModule['label'] }}
        </a>
    @endforeach

    <a
        href="{{ route('teacher.profile.show', $teacherContext->staffId() ?? auth()->id() ?? 0) }}"
        class="rounded border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-700"
    >
        Profile
    </a>

    <a
        href="{{ route('teacher.password.edit') }}"
        class="rounded border border-neutral-200 bg-white px-3 py-2 text-sm text-neutral-700"
    >
        Change Password
    </a>
</nav>
