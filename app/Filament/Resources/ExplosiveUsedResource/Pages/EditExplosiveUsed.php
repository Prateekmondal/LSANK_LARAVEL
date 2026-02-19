<?php

namespace App\Filament\Resources\ExplosiveUsedResource\Pages;

use App\Filament\Resources\ExplosiveUsedResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions;

class EditExplosiveUsed extends EditRecord
{
    protected static string $resource = ExplosiveUsedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
        ];
    }
}
