<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CpfLogin extends BaseLogin
{
    /**
     * Override getForms() — the Filament v3 entry point for building the login form.
     * Replaces the default email field with a CPF (numeric) field.
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getCpfFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getRememberFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getCpfFormComponent(): Component
    {
        return TextInput::make('cpf')
            ->label('CPF')
            ->placeholder('Enter your CPF')
            ->numeric()
            ->required()
            ->autocomplete('off')
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    /**
     * Override getCredentialsFromFormData so Filament's own authenticate()
     * path (rate limiting, etc.) also uses CPF instead of email.
     */
    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'cpf'      => $data['cpf'],
            'password' => $data['password'],
        ];
    }

    /**
     * Custom authenticate() with approval check, super-admin bypass,
     * and tenant-domain restriction for regular users.
     */
    public function authenticate(): ?LoginResponse
    {
        $data = $this->form->getState();

        if (!Auth::attempt([
            'cpf'      => $data['cpf'],
            'password' => $data['password'],
        ], $data['remember'] ?? false)) {
            throw ValidationException::withMessages([
                'data.cpf' => __('auth.failed'),
            ]);
        }

        $user = Auth::user();

        // Unapproved users cannot log in.
        if ($user && !$user->is_approved) {
            Auth::logout();
            throw ValidationException::withMessages([
                'data.cpf' => 'Your account is pending approval by an administrator.',
            ]);
        }

        // Super-admins can access any tenant subdomain — skip the domain restriction.
        if ($user && $user->is_super_admin) {
            session()->regenerate();
            return app(LoginResponse::class);
        }

        // Regular users must log in from their assigned tenant subdomain.
        if ($user && $user->tenant_id) {
            $tenant = \App\Models\Tenant::with('domains')->find($user->tenant_id);
            $currentHost = request()->getHost();
            $allowedDomains = $tenant ? $tenant->domains->pluck('domain')->toArray() : [];

            if (empty($allowedDomains) || !in_array($currentHost, $allowedDomains)) {
                Auth::logout();
                $locationName = ucfirst($user->tenant_id);
                throw ValidationException::withMessages([
                    'data.cpf' => "You can only log in from your assigned location: {$locationName}.",
                ]);
            }
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }
}
