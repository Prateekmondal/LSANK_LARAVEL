<?php

namespace App\Filament\Resources\ExplosiveUsedResource\Pages;

use App\Filament\Resources\ExplosiveUsedResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;

class ViewExplosiveUsed extends ViewRecord
{
    protected static string $resource = ExplosiveUsedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
