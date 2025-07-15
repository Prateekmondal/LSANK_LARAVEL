<?php

namespace App\Filament\Resources\LogTypeResource\Pages;

use App\Filament\Resources\LogTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLogTypes extends ListRecords
{
    protected static string $resource = LogTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
