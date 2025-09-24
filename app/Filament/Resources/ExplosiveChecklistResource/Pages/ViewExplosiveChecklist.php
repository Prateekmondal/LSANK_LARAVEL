<?php

namespace App\Filament\Resources\ExplosiveChecklistResource\Pages;

use App\Filament\Resources\ExplosiveChecklistResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewExplosiveChecklist extends ViewRecord
{
    protected static string $resource = ExplosiveChecklistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
