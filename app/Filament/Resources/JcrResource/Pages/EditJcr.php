<?php

namespace App\Filament\Resources\JcrResource\Pages;

use App\Filament\Resources\JcrResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJcr extends EditRecord
{
    protected static string $resource = JcrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
