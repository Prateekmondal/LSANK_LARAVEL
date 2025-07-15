<?php

namespace App\Filament\Resources\LoggingUnitTypeResource\Pages;

use App\Filament\Resources\LoggingUnitTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLoggingUnitType extends EditRecord
{
    protected static string $resource = LoggingUnitTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
