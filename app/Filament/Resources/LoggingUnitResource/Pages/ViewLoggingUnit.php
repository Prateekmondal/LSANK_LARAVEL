<?php

namespace App\Filament\Resources\LoggingUnitResource\Pages;

use App\Filament\Resources\LoggingUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLoggingUnit extends ViewRecord
{
    protected static string $resource = LoggingUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
