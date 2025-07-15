<?php

namespace App\Filament\Resources\LoggingUnitResource\Pages;

use App\Filament\Resources\LoggingUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLoggingUnit extends EditRecord
{
    protected static string $resource = LoggingUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
