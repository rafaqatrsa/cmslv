<?php

namespace App\Http\Requests;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class StaffLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if (! $this->isMethod('post')) {
            return [];
        }

        return [
            'email' => $this->loginColumn() === 'email'
                ? ['required', 'string', 'email']
                : ['required', 'string', 'max:191'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Attempt to authenticate a staff member with the default web guard.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->credentials(), $this->shouldRemember())) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure repeated staff login attempts are rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the staff login rate limiting key.
     */
    public function throttleKey(): string
    {
        return Str::transliterate('staff|'.Str::lower($this->string('email')).'|'.$this->ip());
    }

    /**
     * Build credentials for either a Laravel users table or the legacy CMS users table.
     *
     * @return array<string, string>
     */
    private function credentials(): array
    {
        $credentials = [
            $this->loginColumn() => $this->string('email')->toString(),
            'password' => $this->string('password')->toString(),
        ];

        if (Schema::hasColumn($this->authTable(), 'is_active')) {
            $credentials['is_active'] = 1;
        }

        return $credentials;
    }

    private function loginColumn(): string
    {
        $table = $this->authTable();
        $login = $this->string('email')->toString();

        if (str_contains($login, '@') && Schema::hasColumn($table, 'email')) {
            return 'email';
        }

        if (Schema::hasColumn($table, 'employee_id')) {
            return 'employee_id';
        }

        if (Schema::hasColumn($table, 'username')) {
            return 'username';
        }

        return 'email';
    }

    private function shouldRemember(): bool
    {
        return $this->boolean('remember') && Schema::hasColumn($this->authTable(), 'remember_token');
    }

    private function authTable(): string
    {
        $provider = config('auth.guards.'.config('auth.defaults.guard', 'web').'.provider', 'users');
        $model = config("auth.providers.{$provider}.model", \App\Models\User::class);

        return (new $model)->getTable();
    }
}
