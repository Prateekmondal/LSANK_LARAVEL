<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CpfLogin extends BaseLogin
{
    public function authenticate(): ?LoginResponse
    {
        $data = $this->form->getState();

        if (!Auth::attempt([
            'cpf' => $data['cpf'],
            'password' => $data['password'],
        ], $data['remember'] ?? false)) {
            throw ValidationException::withMessages([
                'data.cpf' => __('auth.failed'),
            ]);
        }

        session()->regenerate();

        return $this->getResponse($data);
    }

    protected function getFormSchema(): array
    {
        return [
            $this->getCpfFormComponent(),
            $this->getPasswordFormComponent(),
            $this->getRememberFormComponent(),
        ];
    }

    protected function getCpfFormComponent(): Component
    {
        return TextInput::make('cpf')
            ->label('CPF')
            ->placeholder('Enter your CPF')
            ->required()
            ->autocomplete('off')
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Password')
            ->password()
            ->required()
            ->extraInputAttributes(['tabindex' => 2]);
    }

    protected function getRememberFormComponent(): Component
    {
        return Checkbox::make('remember')
            ->label('Remember me');
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'cpf' => $data['cpf'],
            'password' => $data['password'],
        ];
    }
}
