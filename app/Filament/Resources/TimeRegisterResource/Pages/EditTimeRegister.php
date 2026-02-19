<?php

namespace App\Filament\Resources\TimeRegisterResource\Pages;

use App\Filament\Resources\TimeRegisterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTimeRegister extends EditRecord
{
    protected static string $resource = TimeRegisterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
        ];
    }
}
