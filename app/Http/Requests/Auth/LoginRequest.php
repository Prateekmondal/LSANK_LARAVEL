<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cpf' => ['required', 'integer'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('cpf', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'cpf' => trans('auth.failed'),
            ]);
        }

        // Check if user is approved
        $user = Auth::user();
        if ($user && !$user->is_approved) {
            Auth::logout();
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'cpf' => 'Your account is pending approval by an administrator. Please contact support.',
            ]);
        }

        // Super-admins can log in from any tenant subdomain — skip the domain check.
        if ($user && $user->is_super_admin) {
            RateLimiter::clear($this->throttleKey());
            return;
        }

        // Check if user must log in from a specific tenant subdomain
        if ($user && $user->tenant_id) {
            $tenant = \App\Models\Tenant::with('domains')->find($user->tenant_id);
            $currentHost = $this->getHost();

            $allowedDomains = $tenant ? $tenant->domains->pluck('domain')->toArray() : [];

            if (empty($allowedDomains) || !in_array($currentHost, $allowedDomains)) {
                Auth::logout();
                RateLimiter::hit($this->throttleKey());
                $locationName = $user->tenant_id ? ucfirst($user->tenant_id) : 'your assigned location';
                throw ValidationException::withMessages([
                    'cpf' => "You can only log in from your assigned location: {$locationName}. Please visit the correct subdomain.",
                ]);
            }
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
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
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('cpf')).'|'.$this->ip());
    }
}
