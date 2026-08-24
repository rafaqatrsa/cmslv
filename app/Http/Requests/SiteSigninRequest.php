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

class SiteSigninRequest extends FormRequest
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
            'email' => ['required', 'string', 'max:191'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Attempt to authenticate a parent or student against the legacy users table.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::guard('site')->attempt($this->credentials(), $this->shouldRemember()) || ! $this->activeUser()) {
            Auth::guard('site')->logout();
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure repeated site sign-in attempts are rate limited.
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
     * Get the site sign-in rate limiting key.
     */
    public function throttleKey(): string
    {
        return Str::transliterate('site|'.Str::lower($this->string('email')).'|'.$this->ip());
    }

    /**
     * @return array<string, string|int>
     */
    private function credentials(): array
    {
        return [
            $this->loginColumn() => $this->string('email')->toString(),
            'password' => $this->string('password')->toString(),
        ];
    }

    private function shouldRemember(): bool
    {
        return $this->boolean('remember') && Schema::hasColumn('users', 'remember_token');
    }

    private function loginColumn(): string
    {
        return Schema::hasColumn('users', 'username') ? 'username' : 'email';
    }

    private function activeUser(): bool
    {
        if (! Schema::hasColumn('users', 'is_active')) {
            return true;
        }

        $isActive = (string) Auth::guard('site')->user()?->is_active;

        return in_array($isActive, ['1', 'yes', 'active'], true);
    }
}
