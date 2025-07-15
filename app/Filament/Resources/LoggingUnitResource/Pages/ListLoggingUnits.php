<?php

namespace App\Filament\Resources\LoggingUnitResource\Pages;

use App\Filament\Resources\LoggingUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLoggingUnits extends ListRecords
{
    protected static string $resource = LoggingUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
