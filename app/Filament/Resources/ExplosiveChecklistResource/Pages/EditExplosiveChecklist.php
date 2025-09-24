<?php

namespace App\Filament\Resources\ExplosiveChecklistResource\Pages;

use App\Filament\Resources\ExplosiveChecklistResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExplosiveChecklist extends EditRecord
{
    protected static string $resource = ExplosiveChecklistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
