<?php

namespace App\Filament\Resources\JcrResource\Pages;

use App\Filament\Resources\JcrResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJcrs extends ListRecords
{
    protected static string $resource = JcrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
