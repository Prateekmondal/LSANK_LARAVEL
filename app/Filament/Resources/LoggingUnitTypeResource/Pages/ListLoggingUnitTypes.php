<?php

namespace App\Filament\Resources\LoggingUnitTypeResource\Pages;

use App\Filament\Resources\LoggingUnitTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLoggingUnitTypes extends ListRecords
{
    protected static string $resource = LoggingUnitTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
