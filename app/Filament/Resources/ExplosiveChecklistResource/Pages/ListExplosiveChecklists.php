<?php

namespace App\Filament\Resources\ExplosiveChecklistResource\Pages;

use App\Filament\Resources\ExplosiveChecklistResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExplosiveChecklists extends ListRecords
{
    protected static string $resource = ExplosiveChecklistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
