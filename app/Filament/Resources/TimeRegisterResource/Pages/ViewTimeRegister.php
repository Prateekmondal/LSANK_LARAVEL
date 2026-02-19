<?php

namespace App\Filament\Resources\TimeRegisterResource\Pages;

use App\Filament\Resources\TimeRegisterResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTimeRegister extends ViewRecord
{
    protected static string $resource = TimeRegisterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
